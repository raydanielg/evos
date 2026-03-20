@extends('adminlte::page')

@section('title', 'Exams')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Exams</h1>
        <a href="{{ route('exams.create') }}" class="btn btn-primary">Create New Exam</a>
    </div>
@stop

@section('content')
    <div class="card shadow-sm" style="border-radius: 8px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4">Title</th>
                            <th>Date</th>
                            <th>Classes</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exams as $exam)
                            <tr>
                                <td class="px-4 font-weight-bold">
                                    {{ $exam->title }}
                                    <br>
                                    <small class="text-muted">{{ $exam->type?->name }}</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($exam->exam_date)->format('d M, Y') }}</td>
                                <td>
                                    @foreach($exam->examClasses as $ec)
                                        <span class="badge badge-info">{{ $ec->schoolClass->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <span class="badge badge-{{ $exam->status === 'completed' ? 'success' : ($exam->status === 'active' ? 'primary' : 'secondary') }}">
                                        {{ $exam->status === 'created' ? 'Created' : ucfirst($exam->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('exams.show', $exam) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('exams.destroy', $exam) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this exam? All student participation records for this exam will be removed.');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-file-signature fa-3x mb-3 d-block opacity-50"></i>
                                    No exams created yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
