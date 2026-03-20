@extends('adminlte::page')

@section('title', 'Assign Subjects to Classes')

@section('content_header')
    <h1>Assign Subjects to Classes</h1>
    <p class="text-muted">School: {{ $school->name }}</p>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 8px;">
                <i class="icon fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form action="{{ route('subjects.update-class-assignments') }}" method="POST">
            @csrf
            <div class="card shadow-sm" style="border-radius: 8px;">
                <div class="card-header border-0 bg-white pt-4 px-4">
                    <h3 class="card-title text-bold text-muted">Class Subject Matrix</h3>
                    <div class="card-tools">
                        <button type="submit" class="btn btn-success px-4 shadow-sm" style="border-radius: 6px;">
                            <i class="fas fa-save mr-1"></i> Save Assignments
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="min-width: 200px; vertical-align: middle;" class="px-4">Subject \ Class</th>
                                    @foreach($classes as $class)
                                        <th class="text-center" style="min-width: 100px;">
                                            <div class="mb-1">{{ $class->name }}</div>
                                            <button type="button" class="btn btn-xs btn-outline-secondary select-all-class" data-class-id="{{ $class->id }}" title="Select all for this class">
                                                All
                                            </button>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mySubjects as $us)
                                    <tr>
                                        <td class="px-4 font-weight-bold text-muted">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>{{ $us->globalSubject->name }}</span>
                                                <button type="button" class="btn btn-xs btn-outline-secondary select-all-subject" data-subject-id="{{ $us->id }}" title="Select all classes for this subject">
                                                    All
                                                </button>
                                            </div>
                                        </td>
                                        @foreach($classes as $class)
                                            <td class="text-center" style="vertical-align: middle;">
                                                <div class="custom-control custom-checkbox d-inline-block">
                                                    <input class="custom-control-input subject-checkbox class-{{ $class->id }} subject-{{ $us->id }}" type="checkbox" 
                                                           name="assignments[{{ $class->id }}][]" 
                                                           id="assign_{{ $class->id }}_{{ $us->id }}" 
                                                           value="{{ $us->id }}"
                                                           {{ isset($assignments[$class->id]) && in_array($us->id, $assignments[$class->id]) ? 'checked' : '' }}>
                                                    <label for="assign_{{ $class->id }}_{{ $us->id }}" class="custom-control-label" style="cursor: pointer;">&nbsp;</label>
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $classes->count() + 1 }}" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-exclamation-circle fa-2x mb-3"></i>
                                                <p>No subjects found in your profile.</p>
                                                <a href="{{ route('subjects.manage') }}" class="btn btn-primary btn-sm">Manage My Subjects first</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 pb-4 px-4 text-right">
                    <button type="submit" class="btn btn-success px-4 shadow-sm" style="border-radius: 6px;">
                        <i class="fas fa-save mr-1"></i> Save Assignments
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
<script>
$(function() {
    // Select all for a specific subject (across all classes)
    $('.select-all-subject').on('click', function() {
        const subjectId = $(this).data('subject-id');
        const checkboxes = $(`.subject-${subjectId}`);
        const allChecked = checkboxes.length === checkboxes.filter(':checked').length;
        checkboxes.prop('checked', !allChecked);
    });

    // Select all for a specific class (across all subjects)
    $('.select-all-class').on('click', function() {
        const classId = $(this).data('class-id');
        const checkboxes = $(`.class-${classId}`);
        const allChecked = checkboxes.length === checkboxes.filter(':checked').length;
        checkboxes.prop('checked', !allChecked);
    });
});
</script>
@stop

@section('css')
<style>
    .table td, .table th {
        border-top: 1px solid #dee2e6;
    }
    .custom-control-label::before, .custom-control-label::after {
        width: 1.25rem;
        height: 1.25rem;
    }
</style>
@stop
