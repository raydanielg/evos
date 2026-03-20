@extends('adminlte::page')

@section('title', 'Add Student | Evos')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Student</h1>
                @if($activeSchool)
                    <small class="text-muted">Active School: <strong>{{ $activeSchool->name }}</strong></small>
                @endif
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        @if(!$activeSchool)
            <div class="alert alert-warning shadow-sm" style="border-radius: 12px; border: none;">
                <i class="icon fas fa-exclamation-triangle"></i>
                You don't have an active school. Please add/select a school first.
                <a class="ml-2" href="{{ route('schools.create') }}">Add School</a>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card card-warning" style="border-radius: 10px;">
                    <div class="card-header" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <h3 class="card-title">Student Details</h3>
                    </div>

                    <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
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
                                <small class="text-muted">Exam number will be generated later using Assign Numbers / Re-assign Numbers.</small>
                            </div>

                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" id="first_name" placeholder="Enter first name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="middle_name">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control @error('middle_name') is-invalid @enderror" id="middle_name" placeholder="Enter middle name" value="{{ old('middle_name') }}">
                                @error('middle_name')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" id="last_name" placeholder="Enter last name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="sex">Gender</label>
                                <select name="sex" id="sex" class="form-control @error('sex') is-invalid @enderror" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('sex')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="parent_phone">Parent Phone</label>
                                <input type="text" name="parent_phone" class="form-control @error('parent_phone') is-invalid @enderror" id="parent_phone" placeholder="Enter parent phone" value="{{ old('parent_phone') }}" required>
                                @error('parent_phone')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="photo">Student Photo</label>
                                <div class="custom-file">
                                    <input type="file" name="photo" class="custom-file-input @error('photo') is-invalid @enderror" id="photo">
                                    <label class="custom-file-label" for="photo">Choose photo (optional)</label>
                                    @error('photo')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-warning" style="border-radius: 6px;" {{ !$activeSchool ? 'disabled' : '' }}>Save Student</button>
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
            var input = document.getElementById('photo');
            if (!input) return;
            input.addEventListener('change', function () {
                var label = document.querySelector('label[for="photo"]');
                if (!label) return;
                var f = input.files && input.files[0];
                label.textContent = f ? f.name : 'Choose photo (optional)';
            });
        })();
    </script>
@stop

@section('css')
    <style>
        label { font-weight: 600; color: #4b5563; margin-bottom: 0.5rem; }
        .form-control { border-radius: 10px; background-color: #f9fafb; border: 1px solid #e5e7eb; padding: 12px; height: auto; }
        .form-control:focus { background-color: #fff; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }
        .custom-file-label { border-radius: 10px; }
        .custom-file-input:focus ~ .custom-file-label { border-color: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }
    </style>
@stop
