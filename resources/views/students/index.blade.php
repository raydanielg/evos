@extends('adminlte::page')

@section('title', 'Students | Evos')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="mb-0">Students</h1>
                @if($activeSchool)
                    <small class="text-muted">Active School: <strong>{{ $activeSchool->name }}</strong></small>
                @else
                    <small class="text-danger">No active school selected. Add a school first.</small>
                @endif
            </div>
            <div class="col-sm-6">
                <div class="d-flex justify-content-sm-end justify-content-start flex-wrap" style="gap: 8px;">
                    <form method="GET" action="{{ route('students.index') }}" class="d-flex" style="gap: 8px;" id="students-filter-form">
                        <label class="mb-0 d-flex align-items-center" style="font-weight: 700; font-size: 13px;">Exam Centre</label>
                        <select name="class_id" class="form-control" style="min-width: 220px; border-radius: 4px; height: 38px;">
                            <option value="">All</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ (string) $classId === (string) $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="class_id_hidden" value="{{ $classId }}" id="assign-class-id">
                        <label class="mb-0 d-flex align-items-center" style="font-weight: 700; font-size: 13px;">Show</label>
                        <select name="per_page" class="form-control" style="width: 90px; border-radius: 4px; height: 38px;">
                            @foreach(['10','50','100','all'] as $pp)
                                <option value="{{ $pp }}" {{ (string)($perPageRaw ?? '10') === (string)$pp ? 'selected' : '' }}>
                                    {{ $pp === 'all' ? 'All' : $pp }}
                                </option>
                            @endforeach
                        </select>
                        <label class="mb-0 d-flex align-items-center" style="font-weight: 700; font-size: 13px;">Search:</label>
                        <input type="text" name="q" value="{{ $qText ?? '' }}" class="form-control" placeholder="" style="width: 220px; border-radius: 4px; height: 38px;" id="students-search">
                        <button class="btn btn-default" style="border-radius: 4px; height: 38px; white-space: nowrap;">Apply</button>
                    </form>

                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" id="students-columns-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 4px; height: 38px; white-space: nowrap;">
                            Columns
                        </button>
                        <div class="dropdown-menu dropdown-menu-right p-3" aria-labelledby="students-columns-btn" style="min-width: 240px;">
                            <div class="custom-control custom-checkbox mb-2">
                                <input class="custom-control-input evos-col-toggle" type="checkbox" id="col-exam" data-col="exam" checked>
                                <label for="col-exam" class="custom-control-label">Exam Number</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-2">
                                <input class="custom-control-input evos-col-toggle" type="checkbox" id="col-name" data-col="name" checked>
                                <label for="col-name" class="custom-control-label">Full Name</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-2">
                                <input class="custom-control-input evos-col-toggle" type="checkbox" id="col-sex" data-col="sex" checked>
                                <label for="col-sex" class="custom-control-label">Sex</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-2">
                                <input class="custom-control-input evos-col-toggle" type="checkbox" id="col-class" data-col="class" checked>
                                <label for="col-class" class="custom-control-label">Class</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-2">
                                <input class="custom-control-input evos-col-toggle" type="checkbox" id="col-parent" data-col="parent" checked>
                                <label for="col-parent" class="custom-control-label">Parent Phone</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input evos-col-toggle" type="checkbox" id="col-actions" data-col="actions" checked>
                                <label for="col-actions" class="custom-control-label">Actions</label>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('students.create') }}" class="btn btn-primary" style="border-radius: 4px; height: 38px; white-space: nowrap;">
                        <i class="fas fa-user-plus mr-1"></i> Add Student
                    </a>

                    <a href="{{ route('students.import.form') }}" class="btn btn-warning" style="border-radius: 4px; height: 38px; white-space: nowrap;">
                        <i class="fas fa-file-import mr-1"></i> Import
                    </a>

                    <a href="{{ route('students.print', ['class_id' => request('class_id')]) }}" class="btn btn-success" style="border-radius: 4px; height: 38px; white-space: nowrap;">
                        <i class="fas fa-print mr-1"></i> Print Out
                    </a>

                    <form method="POST" action="{{ route('students.reassignNumbers') }}" id="students-reassign-form" onsubmit="return confirm('Re-assign numbers for all students in this class alphabetically?');">
                        @csrf
                        <input type="hidden" name="selected_ids" id="students-reassign-selected">
                        <input type="hidden" name="class_id" value="{{ $classId }}">
                        <button type="submit" class="btn btn-secondary" style="border-radius: 4px; height: 38px; white-space: nowrap;" {{ !$classId ? 'disabled' : '' }}>Re-assign Numbers</button>
                    </form>

                    <form method="POST" action="{{ route('students.assignNumbers') }}" id="students-assign-form">
                        @csrf
                        <input type="hidden" name="selected_ids" id="students-assign-selected">
                        <input type="hidden" name="class_id" value="{{ $classId }}">
                        <button type="submit" class="btn btn-success" style="border-radius: 4px; height: 38px; white-space: nowrap;" {{ !$classId ? 'disabled' : '' }}>Assign Numbers</button>
                    </form>

                    <button type="button" class="btn btn-danger" id="bulk-delete-btn" style="border-radius: 4px; height: 38px; white-space: nowrap;" disabled>
                        <i class="fas fa-trash-alt mr-1"></i> Bulk Delete
                    </button>
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

        <div id="students-table-wrap">
            @if($classId)
                @include('students._table', ['students' => $students, 'paginator' => $paginator])
            @else
                <div class="card shadow-sm" style="border-radius: 8px; border: none;">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-chalkboard-teacher fa-4x text-muted mb-3 opacity-50"></i>
                        <h4 class="text-muted">Select a Class to View Students</h4>
                        <p class="text-muted">Please use the "Exam Centre" dropdown above to filter by class.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Bulk Delete Confirmation Modal -->
    <div class="modal fade" id="bulkDeleteModal" tabindex="-1" role="dialog" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="border-radius: 12px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-danger" id="bulkDeleteModalLabel">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Confirm Bulk Delete
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="bulk-delete-count">0</strong> selected students? This action cannot be undone.</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                    <button type="button" id="confirm-bulk-delete-btn" class="btn btn-danger" style="border-radius: 8px;">Delete Selected</button>
                </div>
            </div>
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
            var wrap = document.getElementById('students-table-wrap');
            var filterForm = document.getElementById('students-filter-form');
            var searchInput = document.getElementById('students-search');
            var assignForm = document.getElementById('students-assign-form');
            var reassignForm = document.getElementById('students-reassign-form');
            var assignSelected = document.getElementById('students-assign-selected');
            var reassignSelected = document.getElementById('students-reassign-selected');

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
                        bindTableHandlers();
                    });

            }

            function getSelectedIds() {
                var ids = [];
                document.querySelectorAll('.students-row-check:checked').forEach(function (cb) {
                    ids.push(cb.value);
                });
                return ids;
            }

            function bindGenerateForms() {
                if (assignForm) {
                    assignForm.addEventListener('submit', function () {
                        var ids = getSelectedIds();
                        assignSelected.value = ids.join(',');
                    });
                }
                if (reassignForm) {
                    reassignForm.addEventListener('submit', function () {
                        var ids = getSelectedIds();
                        reassignSelected.value = ids.join(',');
                    });
                }
            }

            function bindTableHandlers() {
                bindGenerateForms();

                var selectAll = document.getElementById('students-select-all');
                var rowChecks = document.querySelectorAll('.students-row-check');
                var bulkDeleteBtn = document.getElementById('bulk-delete-btn');

                function updateBulkDeleteState() {
                    var selected = getSelectedIds();
                    if (bulkDeleteBtn) {
                        bulkDeleteBtn.disabled = selected.length === 0;
                        var countEl = document.getElementById('bulk-delete-count');
                        if (countEl) countEl.textContent = selected.length;
                    }
                }

                if (selectAll) {
                    selectAll.addEventListener('change', function () {
                        var on = selectAll.checked;
                        document.querySelectorAll('.students-row-check').forEach(function (cb) {
                            cb.checked = on;
                        });
                        updateBulkDeleteState();
                    });
                }

                rowChecks.forEach(function(cb) {
                    cb.addEventListener('change', updateBulkDeleteState);
                });

                document.querySelectorAll('#students-table-wrap .pagination a').forEach(function (a) {
                    a.addEventListener('click', function (e) {
                        e.preventDefault();
                        fetchUpdate(a.getAttribute('href'), true);
                    });
                });

                // Delete confirmation (works after AJAX refresh)
                document.querySelectorAll('#students-table-wrap .students-delete-form').forEach(function (f) {
                    f.addEventListener('submit', function (e) {
                        var name = f.getAttribute('data-student-name') || 'this student';
                        if (!confirm('Delete ' + name + '?')) {
                            e.preventDefault();
                        }
                    });
                });

                // Dropdown delete trigger submits the row form
                document.querySelectorAll('#students-table-wrap .students-delete-trigger').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var row = btn.closest('tr');
                        if (!row) return;
                        var form = row.querySelector('.students-delete-form');
                        if (form) {
                            form.requestSubmit ? form.requestSubmit() : form.submit();
                        }
                    });
                });
            }

            function setColVisible(col, isVisible) {
                var nodes = document.querySelectorAll('.evos-col-' + col);
                nodes.forEach(function (n) {
                    n.style.display = isVisible ? '' : 'none';
                });
            }

            function loadPrefs() {
                try {
                    return JSON.parse(localStorage.getItem('evos_students_cols') || '{}') || {};
                } catch (e) {
                    return {};
                }
            }

            function savePrefs(prefs) {
                localStorage.setItem('evos_students_cols', JSON.stringify(prefs));
            }

            var prefs = loadPrefs();
            document.querySelectorAll('.evos-col-toggle').forEach(function (cb) {
                var col = cb.getAttribute('data-col');
                if (prefs.hasOwnProperty(col)) {
                    cb.checked = !!prefs[col];
                } else {
                    prefs[col] = cb.checked;
                }
                setColVisible(col, cb.checked);

                cb.addEventListener('change', function () {
                    prefs[col] = cb.checked;
                    savePrefs(prefs);
                    setColVisible(col, cb.checked);
                });
            });
            savePrefs(prefs);

            if (filterForm) {
                filterForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    fetchUpdate(window.location.href, true);
                });
            }

            var t = null;
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    clearTimeout(t);
                    t = setTimeout(function () {
                        fetchUpdate(window.location.href, true);
                    }, 350);
                });
            }

            bindTableHandlers();

            // Quick Transfer Logic
            var qtModal = $('#quickTransferModal');
            var qtForm = $('#quickTransferForm');
            var qtSubmitBtn = $('#qt-submit-btn');
            var qtError = $('#qt-error');

            // Bulk Delete Logic
            var bulkDeleteModal = $('#bulkDeleteModal');
            var confirmBulkDeleteBtn = $('#confirm-bulk-delete-btn');

            $(document).on('click', '#bulk-delete-btn', function() {
                var count = getSelectedIds().length;
                $('#bulk-delete-count').text(count);
                bulkDeleteModal.modal('show');
            });

            confirmBulkDeleteBtn.on('click', function() {
                var ids = getSelectedIds();
                if (ids.length === 0) return;

                confirmBulkDeleteBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Deleting...');

                $.ajax({
                    url: '{{ route("students.bulkDelete") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: ids.join(',')
                    },
                    success: function(data) {
                        bulkDeleteModal.modal('hide');
                        confirmBulkDeleteBtn.prop('disabled', false).text('Delete Selected');
                        fetchUpdate(window.location.href, false);
                    },
                    error: function(xhr) {
                        confirmBulkDeleteBtn.prop('disabled', false).text('Delete Selected');
                        alert(xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred during deletion');
                    }
                });
            });

            // AJAX Filtering
            if (filterForm) {
                $(filterForm).find('select').on('change', function() {
                    fetchUpdate(window.location.href, true);
                });
            }

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
                        // Show success toast or alert if needed
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
