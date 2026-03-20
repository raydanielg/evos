@extends('adminlte::page')

@section('title', 'Create Exam')

@section('content_header')
    <h1>Create New Exam</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-outline card-primary shadow-sm" style="border-radius: 8px;">
                <form action="{{ route('exams.store') }}" method="POST">
                    @csrf
                    <div class="card-body px-4">
                        <div class="form-group">
                            <label for="exam_type_id">Exam Type</label>
                            <select name="exam_type_id" id="exam_type_id" class="form-control @error('exam_type_id') is-invalid @enderror" required>
                                <option value="">-- Select Exam Type --</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ old('exam_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('exam_type_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="title">Exam Title</label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Mid-Term I 2026" value="{{ old('title') }}" required>
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="exam_date">Exam Date</label>
                            <input type="date" name="exam_date" id="exam_date" class="form-control @error('exam_date') is-invalid @enderror" value="{{ old('exam_date', date('Y-m-d')) }}" required>
                            @error('exam_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Target Classes</label>
                            <div class="row mt-2">
                                @foreach($classes as $class)
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" name="class_ids[]" id="class_{{ $class->id }}" value="{{ $class->id }}" {{ is_array(old('class_ids')) && in_array($class->id, old('class_ids')) ? 'checked' : '' }}>
                                            <label for="class_{{ $class->id }}" class="custom-control-label font-weight-normal">
                                                {{ $class->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('class_ids')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                            <small class="text-muted d-block mt-2">All students currently in these classes will be automatically registered for this exam.</small>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 pb-4 px-4">
                        <button type="submit" class="btn btn-primary shadow-sm px-4" style="border-radius: 6px;">Create Exam & Register Students</button>
                        <a href="{{ route('exams.index') }}" class="btn btn-light float-right">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0" style="border-radius: 8px; background-color: #f8f9fa;">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-info-circle text-primary mr-2" style="font-size: 1.2rem;"></i>
                        <h5 class="mb-0 text-bold">Information</h5>
                    </div>
                    <p class="text-muted">Creating an exam will:</p>
                    <ul class="text-muted pl-3">
                        <li class="mb-2">Setup the exam record in the system.</li>
                        <li class="mb-2">Automatically pull all students from the selected classes into this exam's participant list.</li>
                        <li>Allow you to enter marks for these students later.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop
