@extends('adminlte::page')

@section('title', 'Exam Details')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Exam: {{ $exam->title }}</h1>
        <div>
            <a href="{{ route('exams.index') }}" class="btn btn-default mr-2">Back to List</a>
            <span class="badge badge-{{ $exam->status === 'completed' ? 'success' : ($exam->status === 'active' ? 'primary' : 'secondary') }} p-2">
                {{ $exam->status === 'created' ? 'Created' : ucfirst($exam->status) }}
            </span>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0" style="border-radius: 8px;">
                <div class="card-header bg-white border-0 pt-4">
                    <h3 class="card-title text-bold text-muted">General Info</h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th class="border-0">Type:</th>
                            <td class="border-0">{{ $exam->type?->name }}</td>
                        </tr>
                        <tr>
                            <th>Date:</th>
                            <td>{{ \Carbon\Carbon::parse($exam->exam_date)->format('d M, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Classes:</th>
                            <td>
                                @foreach($exam->examClasses as $ec)
                                    <span class="badge badge-info">{{ $ec->schoolClass->name }}</span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>Total Students:</th>
                            <td>{{ $exam->participants->count() }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-radius: 8px;">
                <div class="card-header bg-white border-0 pt-4">
                    <h3 class="card-title text-bold text-muted">Participant List</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4">#</th>
                                    <th>Exam Number</th>
                                    <th>Student Name</th>
                                    <th>Class</th>
                                    <th>Sex</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($exam->participants as $p)
                                    <tr>
                                        <td class="px-4 text-muted">{{ $loop->iteration }}</td>
                                        <td class="font-weight-bold">{{ $p->student->registration_number ?? '-' }}</td>
                                        <td>{{ $p->student->full_name }}</td>
                                        <td>{{ $p->schoolClass->name }}</td>
                                        <td>{{ $p->student->sex === 'Male' ? 'M' : 'F' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No participants found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
