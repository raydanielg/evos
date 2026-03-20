@extends('adminlte::page')

@section('title', 'Manage My Subjects')

@section('content_header')
    <h1>Manage My Subjects</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <form action="{{ route('subjects.update-my-subjects') }}" method="POST">
            @csrf
            <div class="card card-outline card-primary shadow-sm" style="border-radius: 8px;">
                <div class="card-header border-0 bg-white pt-4 px-4">
                    <h3 class="card-title text-bold text-muted">Assign Subjects to My Profile</h3>
                </div>
                <div class="card-body px-4">
                    <p class="text-muted mb-4">Select the subjects you will be using in your school management. You can change this list anytime.</p>
                    
                    <div class="row">
                        @foreach($globalSubjects as $subject)
                            <div class="col-md-6 mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" name="subject_ids[]" 
                                           id="subject_{{ $subject->id }}" value="{{ $subject->id }}"
                                           {{ in_array($subject->id, $mySubjectIds) ? 'checked' : '' }}>
                                    <label for="subject_{{ $subject->id }}" class="custom-control-label font-weight-normal text-muted" style="font-size: 1.1rem; cursor: pointer;">
                                        {{ $subject->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer bg-white border-0 pb-4 px-4">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm" style="border-radius: 6px;">
                        Save My Selection
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0" style="border-radius: 8px; background-color: #f8f9fa;">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-info-circle text-primary mr-2" style="font-size: 1.2rem;"></i>
                    <h5 class="mb-0 text-bold">Information</h5>
                </div>
                <p class="text-muted">When you select subjects here:</p>
                <ul class="text-muted pl-3">
                    <li class="mb-2">They will appear in your marks entry forms.</li>
                    <li class="mb-2">They will be used for student performance analysis.</li>
                    <li>Other users will not see your selected list.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .custom-control-label::before, .custom-control-label::after {
        top: 0.25rem;
        width: 1.25rem;
        height: 1.25rem;
    }
    .custom-checkbox .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #007bff;
        border-color: #007bff;
    }
</style>
@stop
