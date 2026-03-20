@extends('adminlte::page')

@section('title', 'Student Profile')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="mb-0">Student Profile</h1>
                @if($activeSchool)
                    <small class="text-muted">Active School: <strong>{{ $activeSchool->name }}</strong></small>
                @else
                    <small class="text-danger">No active school selected. Add a school first.</small>
                @endif
            </div>
            <div class="col-sm-6">
                <div class="d-flex justify-content-sm-end justify-content-start flex-wrap" style="gap: 8px;">
                    <form method="GET" action="{{ route('students.profile') }}" class="d-flex" style="gap: 8px;" id="students-profile-filter-form">
                        <label class="mb-0 d-flex align-items-center" style="font-weight: 700; font-size: 13px;">Class</label>
                        <select name="class_id" class="form-control" style="min-width: 220px; border-radius: 4px; height: 38px;">
                            <option value="">All</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ (string) $classId === (string) $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>

                        <label class="mb-0 d-flex align-items-center" style="font-weight: 700; font-size: 13px;">Name</label>
                        <div class="position-relative" style="width: 260px;">
                            <input type="text" name="q" value="{{ $qText ?? '' }}" class="form-control" placeholder="Search name..." style="border-radius: 4px; height: 38px; padding-right: 34px;" id="students-profile-search">
                            <button type="button" class="btn btn-link" id="students-profile-clear" style="position: absolute; right: 6px; top: 6px; padding: 0; height: 26px; width: 26px; display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <button class="btn btn-default" style="border-radius: 4px; height: 38px; white-space: nowrap;">Apply</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px; border: none;">
                <i class="icon fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div id="students-profile-table-wrap">
            @include('students._profile_table', ['students' => $students])
        </div>
    </div>

    <!-- Quick Transfer Modal -->
    <div class="modal fade" id="quickTransferModal" tabindex="-1" role="dialog" aria-labelledby="quickTransferModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="border-radius: 12px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="quickTransferModalLabel">Transfer <span id="qt-student-name" class="text-warning"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="quickTransferForm">
                    @csrf
                    <input type="hidden" id="qt-student-id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="qt-class-id">Select New Class</label>
                            <select id="qt-class-id" class="form-control" required style="border-radius: 8px;">
                                <option value="">-- Select Class --</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="qt-error" class="alert alert-danger mt-2" style="display:none; border-radius: 8px;"></div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                        <button type="submit" id="qt-submit-btn" class="btn btn-warning" style="border-radius: 8px;">Transfer Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        (function () {
            var wrap = document.getElementById('students-profile-table-wrap');
            var filterForm = document.getElementById('students-profile-filter-form');
            var searchInput = document.getElementById('students-profile-search');
            var clearBtn = document.getElementById('students-profile-clear');

            function buildUrl(url) {
                var u = new URL(url, window.location.origin);
                var params = new URLSearchParams(new FormData(filterForm));
                params.forEach(function (v, k) {
                    u.searchParams.set(k, v);
                });
                return u.toString();
            }

            function fetchUpdate(url, push) {
                if (!wrap) return;
                var finalUrl = buildUrl(url || window.location.href);
                fetch(finalUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        wrap.innerHTML = data.html;
                        if (push) {
                            window.history.pushState({}, '', finalUrl);
                        }
                    });
            }

            function updateClearVisibility() {
                if (!clearBtn) return;
                clearBtn.style.display = (searchInput && searchInput.value && searchInput.value.trim() !== '') ? 'inline-flex' : 'none';
            }

            if (filterForm) {
                filterForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    fetchUpdate(window.location.href, true);
                });

                var classSelect = filterForm.querySelector('select[name="class_id"]');
                if (classSelect) {
                    classSelect.addEventListener('change', function () {
                        fetchUpdate(window.location.href, true);
                    });
                }
            }

            var t = null;
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    clearTimeout(t);
                    updateClearVisibility();
                    t = setTimeout(function () {
                        fetchUpdate(window.location.href, true);
                    }, 300);
                });
            }

            if (clearBtn) {
                clearBtn.addEventListener('click', function () {
                    if (!searchInput) return;
                    searchInput.value = '';
                    updateClearVisibility();
                    fetchUpdate(window.location.href, true);
                });
            }

            updateClearVisibility();

            // Quick Transfer Logic
            var qtModal = $('#quickTransferModal');
            var qtForm = $('#quickTransferForm');
            var qtSubmitBtn = $('#qt-submit-btn');
            var qtError = $('#qt-error');

            $(document).on('click', '.quick-transfer-btn', function() {
                var btn = $(this);
                $('#qt-student-id').val(btn.data('student-id'));
                $('#qt-student-name').text(btn.data('student-name'));
                $('#qt-class-id').val(btn.data('current-class-id'));
                qtError.hide().text('');
                qtModal.modal('show');
            });

            qtForm.on('submit', function(e) {
                e.preventDefault();
                var studentId = $('#qt-student-id').val();
                var classId = $('#qt-class-id').val();
                
                qtSubmitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Transferring...');
                qtError.hide();

                $.ajax({
                    url: '/students/' + studentId + '/quick-transfer',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        class_id: classId
                    },
                    success: function(data) {
                        qtModal.modal('hide');
                        qtSubmitBtn.prop('disabled', false).text('Transfer Student');
                        fetchUpdate(window.location.href, false);
                    },
                    error: function(xhr) {
                        qtSubmitBtn.prop('disabled', false).text('Transfer Student');
                        var msg = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred';
                        qtError.text(msg).show();
                    }
                });
            });
        })();
    </script>
@stop
