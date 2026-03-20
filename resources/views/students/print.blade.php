@extends('adminlte::page')

@section('title', 'Students Print Out | Evos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-0">Students Print Out</h1>
            <small class="text-muted">School: <strong>{{ $activeSchool->name }}</strong></small>
        </div>
        <div>
            <a href="{{ route('students.index', ['class_id' => $classId]) }}" class="btn btn-default" style="border-radius: 6px;">Back</a>
            <button class="btn btn-success" onclick="window.print()" style="border-radius: 6px;">
                <i class="fas fa-print mr-1"></i> Print
            </button>
        </div>
    </div>
@stop

@section('content')
    <div class="card" style="border-radius: 10px;">
        <div class="card-body p-0">
            <table class="table table-bordered table-sm mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 160px;">Reg Number</th>
                        <th>Full Name</th>
                        <th style="width: 120px;">Sex</th>
                        <th style="width: 140px;">Class</th>
                        <th style="width: 160px;">Parent Phone</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td><strong>{{ $student->registration_number }}</strong></td>
                            <td>{{ $student->full_name }}</td>
                            <td>{{ $student->sex }}</td>
                            <td>{{ $student->schoolClass?->name ?? $student->class }}</td>
                            <td>{{ $student->parent_phone }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No students found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <style>
        @media print {
            .main-header, .main-sidebar, .content-header, .content-wrapper .card-header, .btn, .breadcrumb { display: none !important; }
            .content-wrapper { margin-left: 0 !important; }
        }
    </style>
@stop
