@extends('adminlte::page')

@section('title', 'Student Profile | Evos')

@section('content_header')
    <h1>Student Profile: {{ $student->full_name }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-success" style="border-radius: 10px;">
                <div class="card-header" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <h3 class="card-title">Student Information</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        @if($student->photo_path)
                            <img src="{{ asset('storage/'.$student->photo_path) }}" alt="Student Photo" class="img-circle mr-3" style="width: 64px; height: 64px; object-fit: cover;">
                        @else
                            <div class="img-circle mr-3 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px; background: #e5e7eb; color: #111827; font-weight: 800; font-size: 1.2rem;">
                                {{ strtoupper(substr($student->first_name, 0, 1).substr($student->last_name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div style="font-weight: 800; font-size: 1.2rem;">{{ $student->full_name }}</div>
                            <small class="text-muted">Reg: {{ $student->registration_number }}</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Gender</div>
                        <div class="col-sm-8"><strong>{{ $student->sex }}</strong></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Class</div>
                        <div class="col-sm-8"><strong>{{ $student->class }}</strong></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Parent Phone</div>
                        <div class="col-sm-8"><strong>{{ $student->parent_phone }}</strong></div>
                    </div>

                    <hr>
                    <div class="text-right">
                        <a href="{{ route('students.edit', $student) }}" class="btn btn-warning" style="border-radius: 6px;">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <a href="{{ route('students.index') }}" class="btn btn-default" style="border-radius: 6px;">
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
