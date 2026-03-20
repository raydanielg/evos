@extends('adminlte::page')

@section('title', 'Transfer Preview')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Transfer Preview</h1>
                @if($activeSchool)
                    <small class="text-muted">School: <strong>{{ $activeSchool->name }}</strong></small>
                @endif
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 pt-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title text-bold text-muted mb-0">From: {{ $fromClass->name }} → To: {{ $toClass->name }}</h3>
                            <small class="text-muted">Students to transfer: {{ $students->count() }}</small>
                        </div>
                        <div>
                            <a href="{{ route('students.transfer.form') }}" class="btn btn-light mr-2">Back</a>
                            <button type="button" class="btn btn-success" id="startTransferBtn">
                                <i class="fas fa-exchange-alt mr-2"></i> Start Transfer
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="mb-3" id="transferProgressWrap" style="display:none;">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted" id="transferProgressText">Starting...</small>
                                <small class="text-muted" id="transferProgressPercent">0%</small>
                            </div>
                            <div class="progress" style="height: 16px; border-radius: 999px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" id="transferProgressBar"></div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 70px">#</th>
                                        <th>Exam Number</th>
                                        <th>Full Name</th>
                                        <th>Sex</th>
                                        <th>From Class</th>
                                        <th>Parent Phone</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($students as $s)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $s->registration_number ?? '-' }}</td>
                                            <td>{{ $s->full_name }}</td>
                                            <td>{{ $s->sex }}</td>
                                            <td>{{ $fromClass->name }}</td>
                                            <td>{{ $s->parent_phone }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center p-4">No students found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <small class="text-muted">After transfer, numbers will be cleared. Use Assign Numbers / Re-assign Numbers to regenerate.</small>
                        <a href="{{ route('students.index') }}" class="btn btn-default">Go to Students</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        (function () {
            var startBtn = document.getElementById('startTransferBtn');
            var wrap = document.getElementById('transferProgressWrap');
            var bar = document.getElementById('transferProgressBar');
            var txt = document.getElementById('transferProgressText');
            var pct = document.getElementById('transferProgressPercent');

            function csrfToken() {
                var el = document.querySelector('meta[name="csrf-token"]');
                return el ? el.getAttribute('content') : '';
            }

            function setProgress(percent, message, done, total) {
                if (wrap) wrap.style.display = '';
                if (bar) bar.style.width = percent + '%';
                if (pct) pct.textContent = percent + '%';
                if (txt) {
                    var base = message || 'Transferring...';
                    if (typeof done === 'number' && typeof total === 'number') {
                        base += ' (' + done + '/' + total + ')';
                    }
                    txt.textContent = base;
                }
            }

            function step() {
                return fetch("{{ route('students.transfer.progress') }}", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                        'X-CSRF-TOKEN': csrfToken(),
                    },
                    body: 'chunk=50'
                })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    setProgress(data.percent || 0, data.message || '', data.done || 0, data.total || 0);
                    if (data.status === 'done') {
                        if (startBtn) {
                            startBtn.disabled = true;
                            startBtn.innerHTML = '<i class="fas fa-check mr-2"></i> Done';
                        }
                        return;
                    }
                    return new Promise(function (res) { setTimeout(res, 300); }).then(step);
                });
            }

            if (startBtn) {
                startBtn.addEventListener('click', function () {
                    if (startBtn.disabled) return;
                    startBtn.disabled = true;
                    startBtn.innerHTML = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Transferring...';
                    setProgress(0, 'Starting...', 0, 0);
                    step();
                });
            }
        })();
    </script>
@stop
