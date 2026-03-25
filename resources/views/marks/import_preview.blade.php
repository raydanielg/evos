@extends('adminlte::page')

@section('title', 'Marks Import Preview')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Marks Import Preview</h1>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div id="import-progress-container" style="display: none;" class="mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="mb-3">Importing Marks... <span id="import-percentage">0%</span></h5>
                            <div class="progress mb-2" style="height: 25px; border-radius: 12px;">
                                <div id="import-progress-bar" class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <small class="text-muted" id="import-status-text">Preparing to import marks...</small>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 pt-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title text-bold text-muted mb-0">
                                Exam: {{ $exam->name }} | Class: {{ $schoolClass->name }}
                            </h3>
                            <p class="text-muted mb-0">Subject: <strong>{{ $subject->globalSubject->name }}</strong></p>
                            <small class="text-muted">Rows to process: <span id="total-rows-count">{{ count($rows) }}</span></small>
                        </div>
                        <div>
                            <a href="{{ route('marks.entry', ['exam_id' => $exam->id, 'class_id' => $schoolClass->id, 'subject_id' => $subject->id]) }}" class="btn btn-light mr-2" id="backBtn">Back</a>
                            <button type="button" class="btn btn-success" id="confirmBtn">
                                <i class="fas fa-check mr-2"></i> Confirm Import
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0" id="previewTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 70px">#</th>
                                        <th>Index Number</th>
                                        <th>Full Name</th>
                                        <th style="width: 120px" class="text-center">Score</th>
                                        <th style="min-width: 200px">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rows as $r)
                                        <tr class="{{ !$r['is_valid'] ? 'table-danger' : '' }}" 
                                            data-student-id="{{ $r['student_id'] }}" 
                                            data-score="{{ $r['score'] }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $r['index_no'] }}</td>
                                            <td>{{ $r['full_name'] }}</td>
                                            <td class="text-center">
                                                <strong>{{ $r['score'] }}</strong>
                                            </td>
                                            <td class="row-status">
                                                @if(!$r['is_valid'])
                                                    <span class="text-danger"><i class="fas fa-times-circle mr-1"></i> {{ $r['error'] }}</span>
                                                @else
                                                    <span class="text-success"><i class="fas fa-check-circle mr-1"></i> Ready</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 text-right py-3">
                        <button type="button" class="btn btn-success px-4" id="confirmBtn2">
                            <i class="fas fa-check mr-2"></i> Confirm Import
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(function () {
            const confirmBtn = $('#confirmBtn, #confirmBtn2');
            const backBtn = $('#backBtn');
            const progressContainer = $('#import-progress-container');
            const progressBar = $('#import-progress-bar');
            const percentageText = $('#import-percentage');
            const statusText = $('#import-status-text');
            const CHUNK_SIZE = 50;

            confirmBtn.on('click', async function () {
                const rows = [];
                $('#previewTable tbody tr').each(function() {
                    const tr = $(this);
                    if (tr.hasClass('table-danger')) return;

                    rows.push({
                        student_id: tr.data('student-id'),
                        score: tr.data('score'),
                        _tr: tr
                    });
                });

                if (rows.length === 0) {
                    alert('No valid rows to import.');
                    return;
                }

                if (!confirm(`Import ${rows.length} marks?`)) return;

                confirmBtn.prop('disabled', true);
                backBtn.addClass('disabled');
                progressContainer.show();

                let importedCount = 0;
                const total = rows.length;

                for (let i = 0; i < total; i += CHUNK_SIZE) {
                    const chunk = rows.slice(i, i + CHUNK_SIZE);
                    const chunkData = chunk.map(r => ({
                        student_id: r.student_id,
                        score: r.score
                    }));

                    try {
                        const response = await $.ajax({
                            url: "{{ route('marks.import-confirm') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                exam_id: "{{ $exam->id }}",
                                class_id: "{{ $schoolClass->id }}",
                                subject_id: "{{ $subject->id }}",
                                rows: chunkData
                            }
                        });

                        if (response.success) {
                            importedCount += chunk.length;
                            const percent = Math.round((importedCount / total) * 100);
                            progressBar.css('width', percent + '%').attr('aria-valuenow', percent);
                            percentageText.text(percent + '%');
                            statusText.text(`Imported ${importedCount} of ${total} marks...`);

                            chunk.forEach(r => {
                                r._tr.find('.row-status').html('<span class="text-success font-weight-bold">Saved</span>');
                                r._tr.addClass('bg-light').fadeOut(1000);
                            });
                        }
                    } catch (err) {
                        console.error("Import error:", err);
                        statusText.html('<span class="text-danger">Error saving batch. Retrying...</span>');
                        i -= CHUNK_SIZE; // Simple retry logic
                        await new Promise(resolve => setTimeout(resolve, 2000));
                    }
                }

                statusText.html('<span class="text-success font-weight-bold">Import Finished!</span>');
                confirmBtn.hide();
                backBtn.removeClass('disabled').text('Back to Entry').attr('href', "{{ route('marks.entry', ['exam_id' => $exam->id, 'class_id' => $schoolClass->id, 'subject_id' => $subject->id]) }}");
            });
        });
    </script>
@stop
