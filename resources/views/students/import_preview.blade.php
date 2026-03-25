@extends('adminlte::page')

@section('title', 'Import Preview')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Import Preview</h1>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @if(!empty($errors))
                    <div class="alert alert-warning">
                        <strong>Some rows were skipped due to errors:</strong>
                        <ul class="mb-0">
                            @foreach($errors as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div id="import-progress-container" style="display: none;" class="mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="mb-3">Importing Students... <span id="import-percentage">0%</span></h5>
                            <div class="progress mb-2" style="height: 25px; border-radius: 12px;">
                                <div id="import-progress-bar" class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <small class="text-muted" id="import-status-text">Preparing to import 0 students...</small>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 pt-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title text-bold text-muted mb-0">School: {{ $school->name }}</h3>
                            <small class="text-muted">Class: {{ $schoolClass->name }} | Rows ready to import: <span id="total-rows-count">{{ count($rows) }}</span></small>
                        </div>
                        <div>
                            <a href="{{ route('students.import.form') }}" class="btn btn-light mr-2" id="backBtn">Back</a>
                            <button type="button" class="btn btn-success" id="confirmBtn">
                                <i class="fas fa-check mr-2"></i> Confirm Import
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <form id="rowsForm">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mb-0" id="previewTable">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 70px">#</th>
                                            <th>First Name</th>
                                            <th>Middle Name</th>
                                            <th>Last Name</th>
                                            <th>Sex</th>
                                            <th>Parent Phone</th>
                                            <th style="min-width: 240px">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($rows as $r)
                                            <tr class="{{ !empty($r['_error'] ?? null) ? 'table-danger' : '' }}" data-row-index="{{ $loop->index }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <input type="text" class="form-control" name="rows[{{ $loop->index }}][first_name]" value="{{ $r['first_name'] }}" required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="rows[{{ $loop->index }}][middle_name]" value="{{ $r['middle_name'] ?? '' }}">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="rows[{{ $loop->index }}][last_name]" value="{{ $r['last_name'] }}" required>
                                                </td>
                                                <td>
                                                    <select class="form-control" name="rows[{{ $loop->index }}][sex]" required>
                                                        <option value="Male" {{ ($r['sex'] ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                                                        <option value="Female" {{ ($r['sex'] ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="rows[{{ $loop->index }}][parent_phone]" value="{{ $r['parent_phone'] }}">
                                                </td>
                                                <td class="row-status">
                                                    @if(!empty($r['_error'] ?? null))
                                                        <small class="text-danger">{{ $r['_error'] }}</small>
                                                    @else
                                                        <small class="text-muted">Ready</small>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center p-4">No rows found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="p-3 d-flex justify-content-end" style="gap: 8px;">
                                <a href="{{ route('students.import.form') }}" class="btn btn-light" id="backBtn2">Back</a>
                                <button type="button" class="btn btn-success" id="confirmBtn2">
                                    <i class="fas fa-check mr-2"></i> Confirm Import
                                </button>
                            </div>
                        </form>
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
            const backBtn = $('#backBtn, #backBtn2');
            const progressContainer = $('#import-progress-container');
            const progressBar = $('#import-progress-bar');
            const percentageText = $('#import-percentage');
            const statusText = $('#import-status-text');
            const CHUNK_SIZE = 25;

            confirmBtn.on('click', async function () {
                const rows = [];
                $('#previewTable tbody tr').each(function() {
                    const tr = $(this);
                    if (tr.hasClass('table-danger')) return; // Skip rows with preview errors

                    const index = tr.data('row-index');
                    rows.push({
                        first_name: tr.find(`[name="rows[${index}][first_name]"]`).val(),
                        middle_name: tr.find(`[name="rows[${index}][middle_name]"]`).val(),
                        last_name: tr.find(`[name="rows[${index}][last_name]"]`).val(),
                        sex: tr.find(`[name="rows[${index}][sex]"]`).val(),
                        parent_phone: tr.find(`[name="rows[${index}][parent_phone]"]`).val(),
                        _tr: tr
                    });
                });

                if (rows.length === 0) {
                    alert('No valid rows to import.');
                    return;
                }

                if (!confirm(`Are you sure you want to import ${rows.length} students?`)) return;

                confirmBtn.prop('disabled', true);
                backBtn.addClass('disabled');
                progressContainer.show();

                let importedCount = 0;
                const total = rows.length;

                for (let i = 0; i < total; i += CHUNK_SIZE) {
                    const chunk = rows.slice(i, i + CHUNK_SIZE);
                    const chunkData = chunk.map(r => ({
                        first_name: r.first_name,
                        middle_name: r.middle_name,
                        last_name: r.last_name,
                        sex: r.sex,
                        parent_phone: r.parent_phone
                    }));

                    try {
                        const response = await $.ajax({
                            url: "{{ route('students.import.confirm') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                rows: chunkData
                            }
                        });

                        if (response.success) {
                            importedCount += chunk.length;
                            const percent = Math.round((importedCount / total) * 100);
                            progressBar.css('width', percent + '%').attr('aria-valuenow', percent);
                            percentageText.text(percent + '%');
                            statusText.text(`Imported ${importedCount} of ${total} students...`);

                            // Update UI for each row in chunk
                            chunk.forEach(r => {
                                r._tr.fadeOut(400, function() {
                                    $(this).remove();
                                    updateTableCount();
                                });
                            });
                        }
                    } catch (err) {
                        console.error("Import chunk error:", err);
                        const msg = err.responseJSON ? err.responseJSON.message : "Error importing chunk";
                        statusText.html(`<span class="text-danger">Error: ${msg}. Continuing with next batch...</span>`);
                    }
                }

                statusText.html(`<span class="text-success text-bold">Import Complete! ${importedCount} students added.</span>`);
                confirmBtn.hide();
                backBtn.removeClass('disabled').text('Back to Students').attr('href', "{{ route('students.index') }}");
            });

            function updateTableCount() {
                const remaining = $('#previewTable tbody tr').length;
                $('#total-rows-count').text(remaining);
                if (remaining === 0) {
                    $('#previewTable tbody').html('<tr><td colspan="7" class="text-center p-4">All students imported successfully.</td></tr>');
                }
            }
        });
    </script>
@stop
