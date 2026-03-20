@extends('adminlte::page')

@section('title', 'Import Students')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Import Students</h1>
                @if($activeSchool)
                    <small class="text-muted">Active School: <strong>{{ $activeSchool->name }}</strong></small>
                @endif
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

        @if(session('import_errors'))
            <div class="alert alert-warning shadow-sm" style="border-radius: 12px; border: none;">
                <strong>Some rows were skipped due to errors:</strong>
                <ul class="mb-0">
                    @foreach((array) session('import_errors') as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card card-warning" style="border-radius: 10px;">
                    <div class="card-header" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 8px;">
                            <h3 class="card-title mb-0">Upload File</h3>
                            <a href="{{ route('students.import.template') }}" class="btn btn-light" style="border-radius: 6px;">Download Template</a>
                        </div>
                    </div>

                    <form action="{{ route('students.import.preview') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card-body">
                            <div class="form-group">
                                <label for="school_id">School</label>
                                <select name="school_id" id="school_id" class="form-control @error('school_id') is-invalid @enderror" required>
                                    <option value="">Select School</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ old('school_id', $activeSchoolId) == $school->id ? 'selected' : '' }}>
                                            {{ $school->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('school_id')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="class_id">Class</label>
                                <select name="class_id" id="class_id" class="form-control @error('class_id') is-invalid @enderror" required>
                                    <option value="">Select Class</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="file">CSV File</label>
                                <div class="custom-file">
                                    <input type="file" name="file" class="custom-file-input @error('file') is-invalid @enderror" id="file" accept=".csv,text/csv" required>
                                    <label class="custom-file-label" for="file">Choose CSV file</label>
                                    @error('file')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <small class="text-muted d-block mt-2">Use the template above. Columns: <code>first_name</code>, <code>middle_name</code>, <code>last_name</code>, <code>sex</code>, <code>parent_phone</code></small>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-warning" style="border-radius: 6px;">Preview Import</button>
                            <a href="{{ route('students.index') }}" class="btn btn-default float-right" style="border-radius: 6px;">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        (function () {
            var input = document.getElementById('file');
            if (!input) return;
            input.addEventListener('change', function () {
                var label = document.querySelector('label[for="file"]');
                if (!label) return;
                var f = input.files && input.files[0];
                label.textContent = f ? f.name : 'Choose CSV file';
            });
        })();
    </script>
@stop
