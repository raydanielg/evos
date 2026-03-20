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

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 pt-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title text-bold text-muted mb-0">School: {{ $school->name }}</h3>
                            <small class="text-muted">Class: {{ $schoolClass->name }} | Rows ready to import: {{ count($rows) }}</small>
                        </div>
                        <div>
                            <a href="{{ route('students.import.form') }}" class="btn btn-light mr-2">Back</a>
                            <button type="submit" class="btn btn-success" id="confirmBtn" form="rowsForm">
                                <i class="fas fa-check mr-2"></i> Confirm Import
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <form action="{{ route('students.import.confirm') }}" method="POST" id="rowsForm">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 70px">#</th>
                                            <th>First Name</th>
                                            <th>Middle Name</th>
                                            <th>Last Name</th>
                                            <th>Sex</th>
                                            <th>Parent Phone</th>
                                            <th style="min-width: 240px">Error</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($rows as $r)
                                            <tr class="{{ !empty($r['_error'] ?? null) ? 'table-danger' : '' }}">
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
                                                    <input type="text" class="form-control" name="rows[{{ $loop->index }}][parent_phone]" value="{{ $r['parent_phone'] }}" required>
                                                </td>
                                                <td>
                                                    @if(!empty($r['_error'] ?? null))
                                                        <small class="text-danger">{{ $r['_error'] }}</small>
                                                    @else
                                                        <small class="text-muted">OK</small>
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
                                <a href="{{ route('students.import.form') }}" class="btn btn-light">Back</a>
                                <button type="submit" class="btn btn-success" id="confirmBtn2">
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
            $('#rowsForm').on('submit', function () {
                const btn1 = $('#confirmBtn');
                const btn2 = $('#confirmBtn2');
                if (btn2.data('submitted')) return false;
                btn2.data('submitted', true);
                btn1.prop('disabled', true);
                btn2.prop('disabled', true);
                btn1.html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Importing...');
                btn2.html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Importing...');
                return true;
            });
        });
    </script>
@stop
