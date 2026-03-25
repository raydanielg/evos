@extends('adminlte::page')

@section('title', 'Enter Marks')

@section('content_header')
    <h1>Enter Marks</h1>
@stop

@section('content')
<!-- Lock/Unlock PIN Modal -->
<div class="modal fade" id="lockPinModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-bold" id="lockModalTitle">Enter 4-Digit PIN</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="lockPinForm">
                <div class="modal-body text-center">
                    <p class="text-muted small" id="lockModalDesc">Setup a PIN to lock this exam's marks entry.</p>
                    <div class="form-group mb-0">
                        <input type="password" id="entryPin" class="form-control form-control-lg text-center" 
                               maxlength="4" pattern="\d{4}" placeholder="****" required
                               style="letter-spacing: 15px; font-size: 24px; border-radius: 8px;">
                    </div>
                    <div id="pinError" class="text-danger small mt-2" style="display:none;"></div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-primary btn-block shadow-sm" id="confirmPinBtn" style="border-radius: 8px;">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 8px;">
            <i class="icon fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow-sm mb-4" style="border-radius: 8px; background-color: #f8f9fa;">
        <div class="card-body">
            <form action="{{ route('marks.entry') }}" method="GET" id="filter-form">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <div class="form-group mb-0">
                            <label for="exam_id" class="text-bold text-muted small">Exam:</label>
                            <select name="exam_id" id="exam_id" class="form-control select2 shadow-sm" style="border-radius: 6px;" onchange="this.form.submit()">
                                <option value="">-- Choose Exam --</option>
                                @foreach($exams as $exam)
                                    <option value="{{ $exam->id }}" {{ $examId == $exam->id ? 'selected' : '' }}>
                                        {{ $exam->title }} ({{ $exam->type?->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if($examId)
                    <div class="col-md-3">
                        <div class="form-group mb-0">
                            <label for="class_id" class="text-bold text-muted small">Class:</label>
                            <select name="class_id" id="class_id" class="form-control select2 shadow-sm" style="border-radius: 6px;" onchange="this.form.submit()">
                                <option value="">-- Select Class --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ $classId == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

                    @if($classId)
                    <div class="col-md-3">
                        <div class="form-group mb-0">
                            <label for="subject_id" class="text-bold text-muted small">Subject:</label>
                            <select name="subject_id" id="subject_id" class="form-control select2 shadow-sm" style="border-radius: 6px;" onchange="this.form.submit()">
                                <option value="">-- All Subjects --</option>
                                @foreach($subjects as $us)
                                    <option value="{{ $us->id }}" {{ $subjectId == $us->id ? 'selected' : '' }}>
                                        {{ $us->globalSubject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if(!$examId)
        <div class="alert shadow-sm text-white" style="background-color: #17a2b8; border-radius: 8px;">
            <i class="fas fa-info-circle mr-2"></i> Select an exam to start entering marks.
        </div>
    @elseif(!$classId)
        <div class="alert shadow-sm text-white" style="background-color: #17a2b8; border-radius: 8px;">
            <i class="fas fa-info-circle mr-2"></i> Now select a class for the chosen exam.
        </div>
    @else
        <form action="{{ route('marks.store') }}" method="POST">
            @csrf
            <input type="hidden" name="exam_id" value="{{ $examId }}">
            <input type="hidden" name="class_id" value="{{ $classId }}">
            @if($subjectId)
                <input type="hidden" name="user_subject_id" value="{{ $subjectId }}">
            @endif

            @if($subjectId)
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="card shadow-sm border-0" style="border-radius: 8px; background-color: #f8f9fa;">
                            <div class="card-body py-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center" style="gap: 15px;">
                                    <div class="text-muted">
                                        <i class="fas fa-file-excel fa-2x text-success mr-2"></i>
                                        <strong>Excel Import:</strong> Download template, fill scores, and upload back.
                                    </div>
                                    <a href="{{ route('marks.template', ['exam_id' => $examId, 'class_id' => $classId, 'subject_id' => $subjectId]) }}" class="btn btn-sm btn-outline-success shadow-sm" style="border-radius: 6px;">
                                        <i class="fas fa-download mr-1"></i> Download Template
                                    </a>
                                </div>
                                <form action="{{ route('marks.import-preview') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center" style="gap: 10px;">
                                    @csrf
                                    <input type="hidden" name="exam_id" value="{{ $examId }}">
                                    <input type="hidden" name="class_id" value="{{ $classId }}">
                                    <input type="hidden" name="subject_id" value="{{ $subjectId }}">
                                    <div class="custom-file" style="width: 250px;">
                                        <input type="file" name="file" class="custom-file-input custom-file-input-sm" id="importFile" accept=".csv" required onchange="$(this).next('.custom-file-label').html(this.files[0].name)">
                                        <label class="custom-file-label custom-file-label-sm" for="importFile">Choose CSV</label>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-success shadow-sm" style="border-radius: 6px;">
                                        <i class="fas fa-upload mr-1"></i> Import Marks
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card shadow-sm border-0" style="border-radius: 8px;">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title text-bold text-muted">
                            Marks Entry: {{ $students->count() }} Students 
                            @if($subjectId) 
                                - {{ $subjects->firstWhere('id', $subjectId)->globalSubject->name }}
                            @else
                                (All Subjects)
                            @endif
                        </h3>
                        <div class="d-flex align-items-center" style="gap: 15px;">
                            <button type="button" class="btn btn-sm btn-{{ ($examLock && $examLock->is_locked) ? 'danger' : 'outline-primary' }} px-3 shadow-sm" 
                                    id="lockPinBtn" style="border-radius: 6px;">
                                <i class="fas fa-{{ ($examLock && $examLock->is_locked) ? 'lock' : 'unlock' }} mr-1"></i>
                                <span id="lockBtnText">{{ ($examLock && $examLock->is_locked) ? 'Locked' : 'Lock Entry' }}</span>
                            </button>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="autoSaveToggle">
                                <label class="custom-control-label text-muted font-weight-normal" for="autoSaveToggle">
                                    AutoSave <span id="autoSaveStatus" class="small text-uppercase ml-1">OFF</span>
                                </label>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle px-3 shadow-sm" type="button" id="columnToggle" data-toggle="dropdown" style="border-radius: 6px;">
                                    <i class="fas fa-columns mr-1"></i> Columns
                                </button>
                                <div class="dropdown-menu dropdown-menu-right p-3" aria-labelledby="columnToggle" style="min-width: 200px;">
                                    <h6 class="dropdown-header px-0">Toggle Visibility</h6>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input col-toggle" id="toggle-exam-no" data-col="exam-no" checked>
                                        <label class="custom-control-label font-weight-normal" for="toggle-exam-no">Exam No</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input col-toggle" id="toggle-full-name" data-col="full-name" checked>
                                        <label class="custom-control-label font-weight-normal" for="toggle-full-name">Full Name</label>
                                    </div>
                                    @if(!$subjectId)
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input col-toggle" id="toggle-total" data-col="total" checked>
                                            <label class="custom-control-label font-weight-normal" for="toggle-total">Total Score</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input col-toggle" id="toggle-avg" data-col="avg" checked>
                                            <label class="custom-control-label font-weight-normal" for="toggle-avg">Average</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input col-toggle" id="toggle-grade" data-col="grade" checked>
                                            <label class="custom-control-label font-weight-normal" for="toggle-grade">Grade</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input col-toggle" id="toggle-points" data-col="points" checked>
                                            <label class="custom-control-label font-weight-normal" for="toggle-points">Points</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input col-toggle" id="toggle-division" data-col="division" checked>
                                            <label class="custom-control-label font-weight-normal" for="toggle-division">Division</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input col-toggle" id="toggle-position" data-col="position" checked>
                                            <label class="custom-control-label font-weight-normal" for="toggle-position">Position</label>
                                        </div>
                                        <div class="dropdown-divider"></div>
                                        @foreach($subjects as $sub)
                                            <div class="custom-control custom-checkbox mb-2">
                                                <input type="checkbox" class="custom-control-input col-toggle" id="toggle-sub-{{ $sub->id }}" data-col="sub-{{ $sub->id }}" checked>
                                                <label class="custom-control-label font-weight-normal" for="toggle-sub-{{ $sub->id }}">{{ $sub->globalSubject->code ?? $sub->globalSubject->name }}</label>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success px-4 shadow-sm" id="manualSaveBtn" style="border-radius: 6px;">
                                <i class="fas fa-save mr-1"></i> Save All Marks
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 table-bordered table-sm w-100">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-2 text-center mark-col-sn" style="width: 40px; vertical-align: middle;">S/N</th>
                                    <th class="mark-col-exam-no" style="vertical-align: middle; min-width: 120px;">Exam No</th>
                                    <th class="mark-col-full-name" style="vertical-align: middle; min-width: 200px;">Full Name</th>
                                    @if($subjectId)
                                        <th style="width: 100px;" class="text-center mark-col-marks">Marks</th>
                                    @else
                                        @foreach($subjects as $sub)
                                            <th class="text-center mark-col-sub-{{ $sub->id }}" style="min-width: 60px; vertical-align: middle;" title="{{ $sub->globalSubject->name }}">
                                                {{ $sub->globalSubject->code ?? substr($sub->globalSubject->name, 0, 3) }}
                                            </th>
                                        @endforeach
                                        <th class="text-center mark-col-total" style="width: 80px; background-color: #f8f9fa; vertical-align: middle;">Total</th>
                                        <th class="text-center mark-col-avg" style="width: 80px; background-color: #f8f9fa; vertical-align: middle;">Avg</th>
                                        <th class="text-center mark-col-grade" style="width: 60px; background-color: #f8f9fa; vertical-align: middle;">Grd</th>
                                        <th class="text-center mark-col-points" style="width: 60px; background-color: #f8f9fa; vertical-align: middle;">Pts</th>
                                        <th class="text-center mark-col-division" style="width: 60px; background-color: #f8f9fa; vertical-align: middle;">Div</th>
                                        <th class="text-center mark-col-position" style="width: 60px; background-color: #f8f9fa; vertical-align: middle;">Pos</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    <tr>
                                        <td class="px-2 text-center text-muted mark-col-sn">{{ $loop->iteration }}</td>
                                        <td class="font-weight-bold mark-col-exam-no">{{ $student->registration_number ?? '-' }}</td>
                                        <td class="mark-col-full-name text-nowrap">
                                            {{ $student->full_name }}
                                        </td>
                                        @if($subjectId)
                                            <td class="mark-col-marks">
                                                <input type="number" name="marks[{{ $student->id }}]" 
                                                       class="form-control form-control-sm text-center shadow-none mark-input" 
                                                       step="1" min="0" max="100"
                                                       value="{{ $student->marks->first()?->score ? (int)$student->marks->first()?->score : $student->marks->first()?->score }}"
                                                       data-student-id="{{ $student->id }}"
                                                       data-subject-id="{{ $subjectId }}"
                                                       style="border-radius: 4px; font-weight: bold; border: 1px solid #ced4da;">
                                            </td>
                                        @else
                                            @php 
                                                $res = $student->examResults->first();
                                                $total = $res ? $res->total_marks : 0;
                                                $avg = $res ? $res->average : 0;
                                                $points = $res ? $res->total_points : '-';
                                                $division = $res ? $res->division : '-';
                                                $position = $res ? $res->position : '-';
                                                $isComplete = $res ? $res->is_complete : false;
                                                $hasAny = $student->marks->count() > 0;
                                            @endphp
                                            @foreach($subjects as $sub)
                                                @php 
                                                    $mark = $student->marks->where('user_subject_id', $sub->id)->first();
                                                    $score = $mark ? $mark->score : null;
                                                @endphp
                                                <td class="p-1 mark-col-sub-{{ $sub->id }}">
                                                    <div class="position-relative">
                                                        <input type="number" name="marks[{{ $student->id }}][{{ $sub->id }}]" 
                                                               class="form-control form-control-sm text-center mark-input-cell p-0" 
                                                               step="1" min="0" max="100"
                                                               value="{{ $score !== null ? (int)$score : null }}"
                                                               data-student-id="{{ $student->id }}"
                                                               data-subject-id="{{ $sub->id }}"
                                                               style="border-radius: 3px; font-size: 12px; height: 28px;">
                                                        @if($score !== null)
                                                            <i class="fas fa-check text-success position-absolute" style="right: 2px; top: 8px; font-size: 8px;"></i>
                                                        @endif
                                                    </div>
                                                </td>
                                            @endforeach
                                            <td class="text-center font-weight-bold mark-col-total" style="background-color: #f8f9fa; font-size: 13px;">
                                                <span class="total-val">{{ $hasAny ? (int)$total : '-' }}</span>
                                            </td>
                                            <td class="text-center mark-col-avg" style="background-color: #f8f9fa; font-size: 13px;">
                                                <span class="avg-val">{{ $hasAny ? ($isComplete ? (int)round($avg) : 'INC') : '-' }}</span>
                                            </td>
                                            <td class="text-center mark-col-grade" style="background-color: #f8f9fa;">
                                                @php
                                                    $grade = '-';
                                                    if($hasAny && $isComplete) {
                                                        if ($avg >= 75) $grade = 'A';
                                                        elseif ($avg >= 65) $grade = 'B';
                                                        elseif ($avg >= 45) $grade = 'C';
                                                        elseif ($avg >= 30) $grade = 'D';
                                                        else $grade = 'F';
                                                    } elseif($hasAny) {
                                                        $grade = 'INC';
                                                    }
                                                @endphp
                                                <span class="grade-val">{{ $hasAny ? ($isComplete ? $grade : 'INC') : '-' }}</span>
                                            </td>
                                            <td class="text-center mark-col-points" style="background-color: #f8f9fa;">
                                                <span class="points-val">{{ $hasAny ? ($isComplete ? $points : 'INC') : '-' }}</span>
                                            </td>
                                            <td class="text-center mark-col-division" style="background-color: #f8f9fa;">
                                                <span class="division-val">{{ $hasAny ? ($isComplete ? $division : 'INC') : '-' }}</span>
                                            </td>
                                            <td class="text-center mark-col-position" style="background-color: #f8f9fa;">
                                                <span class="position-val">{{ $hasAny && $isComplete ? $position : '-' }}</span>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 pb-4 px-4 text-right">
                    <button type="submit" class="btn btn-success px-4 shadow-sm" style="border-radius: 6px;">
                        <i class="fas fa-save mr-1"></i> Save All Marks
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>
@stop

@section('js')
<script>
$(function() {
    const isLockedInitially = "{{ ($examLock && $examLock->is_locked) ? 'true' : 'false' }}" === 'true';
    const lockPinBtn = $('#lockPinBtn');
    const lockBtnText = $('#lockBtnText');
    const lockPinModal = $('#lockPinModal');
    const lockPinForm = $('#lockPinForm');
    const entryPin = $('#entryPin');
    const pinError = $('#pinError');
    const allMarkInputs = $('.mark-input, .mark-input-cell');

    // AutoSave Toggle Logic
    const autoSaveToggle = $('#autoSaveToggle');
    const autoSaveStatus = $('#autoSaveStatus');
    const manualSaveBtn = $('#manualSaveBtn');

    function applyLockUI(isLocked) {
        allMarkInputs.prop('readonly', isLocked);
        if (isLocked) {
            lockPinBtn.removeClass('btn-outline-primary').addClass('btn-danger');
            lockPinBtn.find('i').removeClass('fa-unlock').addClass('fa-lock');
            lockBtnText.text('Locked');
            allMarkInputs.css('background-color', '#f8f9fa').css('cursor', 'not-allowed');
            if (manualSaveBtn.length) manualSaveBtn.hide();
            if (autoSaveToggle.length) autoSaveToggle.prop('disabled', true);
        } else {
            lockPinBtn.removeClass('btn-danger').addClass('btn-outline-primary');
            lockPinBtn.find('i').removeClass('fa-lock').addClass('fa-unlock');
            lockBtnText.text('Lock Entry');
            allMarkInputs.css('background-color', '').css('cursor', 'auto');
            if (autoSaveToggle.length) autoSaveToggle.prop('disabled', false);
            if (autoSaveToggle.length && manualSaveBtn.length && !autoSaveToggle.is(':checked')) manualSaveBtn.show();
            allMarkInputs.trigger('input');
        }
    }

    applyLockUI(isLockedInitially);

    lockPinBtn.on('click', function() {
        const isCurrentlyLocked = lockPinBtn.hasClass('btn-danger');
        if (isCurrentlyLocked) {
            $('#lockModalTitle').text('Unlock Entry');
            $('#lockModalDesc').text('Enter your 4-digit PIN to unlock.');
        } else {
            const hasLockRecord = "{{ $examLock ? 'true' : 'false' }}" === 'true';
            $('#lockModalTitle').text(hasLockRecord ? 'Lock Entry' : 'Setup Lock PIN');
            $('#lockModalDesc').text(hasLockRecord ? 'Enter your PIN to lock marks entry.' : 'Create a 4-digit PIN to protect these marks.');
        }
        entryPin.val('');
        pinError.hide();
        lockPinModal.modal('show');
    });

    lockPinForm.on('submit', function(e) {
        e.preventDefault();
        const pin = entryPin.val();
        if (!/^\d{4}$/.test(pin)) {
            pinError.text('PIN must be 4 digits.').show();
            return;
        }

        const isCurrentlyLocked = lockPinBtn.hasClass('btn-danger');
        const action = isCurrentlyLocked ? 'unlock' : 'lock';

        $.ajax({
            url: "{{ route('marks.toggle-lock') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                exam_id: "{{ $examId }}",
                pin: pin,
                action: action
            },
            success: function(data) {
                lockPinModal.modal('hide');
                applyLockUI(action === 'lock');
                // Optional: Toast message
            },
            error: function(xhr) {
                const msg = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred';
                pinError.text(msg).show();
            }
        });
    });

    autoSaveToggle.on('change', function() {
        const isOn = $(this).is(':checked');
        autoSaveStatus.text(isOn ? 'ON' : 'OFF').toggleClass('text-primary', isOn);
        manualSaveBtn.toggle(!isOn);
        localStorage.setItem('mark_entry_autosave', isOn);
    });

    // Load AutoSave preference
    const isAutoSaveOn = localStorage.getItem('mark_entry_autosave') === 'true';
    autoSaveToggle.prop('checked', isAutoSaveOn).trigger('change');

    // AJAX Save Function
    function saveMark(studentId, subjectId, score, inputElement, forceAjax = false) {
        const container = inputElement.closest('.position-relative');
        container.find('.fa-check').remove();
        
        // Validation colors
        inputElement.removeClass('border-success border-danger border-warning');
        if (score === '') {
            inputElement.css('background-color', '');
        } else if (score > 100 || score < 0) {
            inputElement.addClass('border-danger').css('background-color', '#fff5f5');
            updateLiveCalculations(inputElement.closest('tr'));
            if (!inputElement.data('range-alert-shown')) {
                alert('Marks must be between 0 and 100.');
                inputElement.data('range-alert-shown', true);
            }
            return; // Don't save invalid scores
        } else {
            inputElement.addClass('border-success').css('background-color', '#f0fff4');
            inputElement.removeData('range-alert-shown');
        }

        updateLiveCalculations(inputElement.closest('tr'));

        if (!autoSaveToggle.is(':checked') && !forceAjax) return;

        inputElement.addClass('border-warning');

        $.ajax({
            url: "{{ route('marks.store-single') }}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                exam_id: "{{ $examId }}",
                class_id: "{{ $classId }}",
                student_id: studentId,
                user_subject_id: subjectId,
                score: score
            },
            success: function(data) {
                inputElement.removeClass('border-warning').addClass('border-success');
                if (container.length) {
                    container.append('<i class="fas fa-check text-success position-absolute" style="right: 2px; top: 8px; font-size: 8px;"></i>');
                }
                
                // Update results if data contains them
                if (data.results) {
                    const trs = $('.mark-input, .mark-input-cell').closest('tr');
                    
                    const updateWithFade = (tr, selector, newVal) => {
                        const el = tr.find(selector);
                        if (el.length && el.text() !== String(newVal)) {
                            el.fadeOut(200, function() {
                                $(this).text(newVal).fadeIn(200).addClass('text-primary');
                                setTimeout(() => $(this).removeClass('text-primary'), 2000);
                            });
                        }
                    };

                    // Update EVERY row in the visible table because positions might have shifted for anyone
                    trs.each(function() {
                        const tr = $(this);
                        const sId = tr.find('.mark-input, .mark-input-cell').first().data('student-id');
                        if (data.results[sId]) {
                            const res = data.results[sId];
                            updateWithFade(tr, '.total-val', res.total_marks);
                            updateWithFade(tr, '.avg-val', res.is_complete ? res.average : 'INC');
                            updateWithFade(tr, '.grade-val', res.is_complete ? res.grade : 'INC');
                            updateWithFade(tr, '.points-val', res.is_complete ? res.total_points : 'INC');
                            updateWithFade(tr, '.division-val', res.is_complete ? res.division : 'INC');
                            updateWithFade(tr, '.position-val', res.is_complete ? res.position : '-');
                        }
                    });
                }
            },
            error: function() {
                inputElement.removeClass('border-warning').addClass('border-danger');
            }
        });
    }

    function updateLiveCalculations(row) {
        if ("{{ $subjectId }}") return; // Only for matrix view

        let total = 0;
        let count = 0;
        let subjectsTotal = parseInt("{{ $subjects->count() }}");
        let hasAny = false;

        row.find('.mark-input-cell').each(function() {
            const val = parseFloat($(this).val());
            if (!isNaN(val)) {
                total += val;
                count++;
                hasAny = true;
            }
        });

        const totalSpan = row.find('.total-val');
        const avgSpan = row.find('.avg-val');
        const gradeSpan = row.find('.grade-val');
        const pointsSpan = row.find('.points-val');
        const divisionSpan = row.find('.division-val');
        const posSpan = row.find('.position-val');

        if (!hasAny) {
            totalSpan.text('-');
            avgSpan.text('-');
            gradeSpan.text('-');
            pointsSpan.text('-');
            divisionSpan.text('-');
            posSpan.text('-');
            return;
        }

        totalSpan.text(Math.round(total));

        if (count < 7) {
            avgSpan.text('INC');
            gradeSpan.text('INC');
            pointsSpan.text('INC');
            divisionSpan.text('INC');
            posSpan.text('-');
        } else {
            const avg = total / count;
            avgSpan.text(Math.round(avg));

            // Calculate points for all subjects and take best 7
            let allPoints = [];
            row.find('.mark-input-cell').each(function() {
                const s = parseFloat($(this).val());
                if (!isNaN(s)) {
                    if (s >= 75) allPoints.push(1);
                    else if (s >= 65) allPoints.push(2);
                    else if (s >= 45) allPoints.push(3);
                    else if (s >= 30) allPoints.push(4);
                    else allPoints.push(5);
                }
            });

            allPoints.sort((a, b) => a - b); // Sort ascending (1 is better than 5)
            const best7Points = allPoints.slice(0, 7);
            const totalPoints = best7Points.reduce((a, b) => a + b, 0);

            let grade = 'F';
            if (avg >= 75) { grade = 'A'; }
            else if (avg >= 65) { grade = 'B'; }
            else if (avg >= 45) { grade = 'C'; }
            else if (avg >= 30) { grade = 'D'; }

            let division = 'IV';
            if (totalPoints <= 17) division = 'I';
            else if (totalPoints <= 21) division = 'II';
            else if (totalPoints <= 25) division = 'III';
            else if (totalPoints <= 33) division = 'IV';
            else division = '0';

            gradeSpan.text(grade);
            pointsSpan.text(totalPoints);
            divisionSpan.text(division);
            
            // Note: Positions update on page save/refresh
        }
    }

    // Enter Key Navigation & Validation
    $(document).on('keydown', '.mark-input, .mark-input-cell', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            const currentInput = $(this);
            const inputs = $('.mark-input, .mark-input-cell');
            const index = inputs.index(currentInput);
            
            // Navigate to next student same subject (down)
            // In matrix view, next input might be next subject, so we find input in same column next row
            const td = currentInput.closest('td');
            const colIndex = td.index();
            const nextRow = td.closest('tr').next('tr');
            const nextInput = nextRow.find('td').eq(colIndex).find('input');

            if (nextInput.length) {
                nextInput.focus().select();
            } else {
                // If last student, maybe move to top of next subject column?
                // For now just blur
                currentInput.blur();
            }
        }
    });

    $(document).on('input', '.mark-input, .mark-input-cell', function() {
        const val = $(this).val();
        if (val === '') {
            $(this).css('background-color', '');
        } else if (val > 100 || val < 0) {
            $(this).css('background-color', '#fff5f5').addClass('border-danger').removeClass('border-success');
        } else {
            $(this).css('background-color', '#f0fff4').addClass('border-success').removeClass('border-danger');
        }
    });

    $(document).on('blur', '.mark-input, .mark-input-cell', function() {
        const raw = $(this).val();
        if (raw === '') return;
        const num = parseFloat(raw);
        if (!isNaN(num) && (num < 0 || num > 100)) {
            if (!$(this).data('range-alert-shown')) {
                alert('Marks must be between 0 and 100.');
                $(this).data('range-alert-shown', true);
            }
        } else {
            $(this).removeData('range-alert-shown');
        }
    });

    $('.mark-input').on('change', function() {
        const input = $(this);
        const studentId = input.data('student-id');
        const subjectId = input.data('subject-id');
        const raw = input.val();
        
        if (raw !== '') {
            const num = parseFloat(raw);
            if (!isNaN(num) && (num < 0 || num > 100)) {
                input.css('background-color', '#fff5f5').addClass('border-danger').removeClass('border-success');
                if (!input.data('range-alert-shown')) {
                    alert('Marks must be between 0 and 100.');
                    input.data('range-alert-shown', true);
                }
                return;
            }
        }
        input.removeData('range-alert-shown');
        
        // Always save via AJAX regardless of AutoSave toggle
        saveMark(studentId, subjectId, raw, input, true);
    });

    $('.mark-input-cell').on('change', function() {
        const input = $(this);
        const studentId = input.data('student-id');
        const subjectId = input.data('subject-id');
        const raw = input.val();
        
        if (raw !== '') {
            const num = parseFloat(raw);
            if (!isNaN(num) && (num < 0 || num > 100)) {
                input.css('background-color', '#fff5f5').addClass('border-danger').removeClass('border-success');
                if (!input.data('range-alert-shown')) {
                    alert('Marks must be between 0 and 100.');
                    input.data('range-alert-shown', true);
                }
                return;
            }
        }
        input.removeData('range-alert-shown');
        
        // Always save via AJAX regardless of AutoSave toggle
        saveMark(studentId, subjectId, raw, input, true);
    });

    // Column Toggle Logic
    $('.col-toggle').on('change', function() {
        const col = $(this).data('col');
        const isVisible = $(this).is(':checked');
        
        if (isVisible) {
            $(`.mark-col-${col}`).show();
        } else {
            $(`.mark-col-${col}`).hide();
        }
        
        // Save preference
        const prefs = JSON.parse(localStorage.getItem('mark_entry_cols') || '{}');
        prefs[col] = isVisible;
        localStorage.setItem('mark_entry_cols', JSON.stringify(prefs));
    });

    // Load preferences
    const prefs = JSON.parse(localStorage.getItem('mark_entry_cols') || '{}');
    Object.keys(prefs).forEach(col => {
        const isVisible = prefs[col];
        $(`#toggle-${col}`).prop('checked', isVisible);
        if (!isVisible) {
            $(`.mark-col-${col}`).hide();
        }
    });
});
</script>
@stop

@section('css')
<style>
    .badge-pink { background-color: #ff69b4; color: white; }
    .badge-blue { background-color: #007bff; color: white; }
    .mark-input:focus, .mark-input-cell:focus { border-color: #28a745 !important; box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important; outline: none; }
    .table td, .table th { vertical-align: middle; border-top: 1px solid #f2f2f2; }
    /* Remove spin buttons from number inputs */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
    .mark-input-cell, .mark-input {
        transition: all 0.2s;
    }
    .select2-container--default .select2-selection--single { height: 38px !important; border-radius: 6px !important; border: 1px solid #ced4da !important; }
</style>
@stop
