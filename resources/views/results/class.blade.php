@extends('adminlte::page')

@section('title', 'Class Results')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="mb-0">Class Results</h1>
                <small class="text-muted">Summary of results per class for a selected exam.</small>
            </div>
            <div class="col-sm-6">
                <form method="GET" action="{{ route('results.class') }}" class="d-flex justify-content-sm-end justify-content-start flex-wrap" style="gap: 8px;">
                    <label class="mb-0 d-flex align-items-center" style="font-weight: 700; font-size: 13px;">Exam</label>
                    <select name="exam_id" class="form-control" style="min-width: 260px; border-radius: 4px; height: 38px;" onchange="this.form.submit()">
                        <option value="">-- Choose Exam --</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ (string)$examId === (string)$exam->id ? 'selected' : '' }}>
                                {{ $exam->title }} ({{ $exam->type?->name }})
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-default" style="border-radius: 4px; height: 38px; white-space: nowrap;">Apply</button>
                </form>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        @if($examId)
            <div class="card shadow-sm" style="border-radius: 10px; border: none;">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4" style="width:60px;">#</th>
                                    <th style="min-width: 200px;">Class</th>
                                    <th class="text-center" style="width: 120px;">Students</th>
                                    <th class="text-center" style="width: 120px;">Complete</th>
                                    <th class="text-center" style="width: 120px;">Incomplete</th>
                                    <th class="text-center" style="width: 140px;">Avg Total</th>
                                    <th class="text-center" style="width: 140px;">Avg Average</th>
                                    <th style="min-width: 260px;">Divisions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rows as $row)
                                    <tr>
                                        <td class="px-4 text-muted">{{ $loop->iteration }}</td>
                                        <td class="font-weight-bold">{{ $row->class->name }}</td>
                                        <td class="text-center">{{ $row->total }}</td>
                                        <td class="text-center"><span class="badge badge-success" style="border-radius:999px; padding:6px 10px;">{{ $row->complete }}</span></td>
                                        <td class="text-center"><span class="badge badge-warning" style="border-radius:999px; padding:6px 10px;">{{ $row->incomplete }}</span></td>
                                        <td class="text-center">{{ number_format((float)$row->avg_total, 2) }}</td>
                                        <td class="text-center">{{ number_format((float)$row->avg_average, 2) }}</td>
                                        <td>
                                            <div style="display:flex; gap: 6px; flex-wrap: wrap;">
                                                @forelse($row->divisions as $div => $cnt)
                                                    <span class="badge badge-light" style="border:1px solid #e9ecef; border-radius:999px; padding:6px 10px;">{{ $div }}: <strong>{{ $cnt }}</strong></span>
                                                @empty
                                                    <span class="text-muted">-</span>
                                                @endforelse
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <i class="fas fa-poll-h fa-3x mb-3 d-block opacity-50"></i>
                                            No class results found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="card shadow-sm" style="border-radius: 10px; border: none;">
                <div class="card-body text-center py-5">
                    <i class="fas fa-filter fa-4x text-muted mb-3 opacity-50"></i>
                    <h4 class="text-muted">Select an Exam</h4>
                    <p class="text-muted mb-0">Choose an exam to view class summaries.</p>
                </div>
            </div>
        @endif
    </div>
@stop
