@extends('adminlte::page')

@section('title', 'Position Ranking')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="mb-0">Position Ranking</h1>
                <small class="text-muted">Rank students by position for a selected exam (and optionally class).</small>
            </div>
            <div class="col-sm-6">
                <form method="GET" action="{{ route('results.ranking') }}" class="d-flex justify-content-sm-end justify-content-start flex-wrap" style="gap: 8px;">
                    <label class="mb-0 d-flex align-items-center" style="font-weight: 700; font-size: 13px;">Exam</label>
                    <select name="exam_id" class="form-control" style="min-width: 240px; border-radius: 4px; height: 38px;" onchange="this.form.submit()">
                        <option value="">-- Choose Exam --</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ (string)$examId === (string)$exam->id ? 'selected' : '' }}>
                                {{ $exam->title }} ({{ $exam->type?->name }})
                            </option>
                        @endforeach
                    </select>

                    <label class="mb-0 d-flex align-items-center" style="font-weight: 700; font-size: 13px;">Class</label>
                    <select name="class_id" class="form-control" style="min-width: 180px; border-radius: 4px; height: 38px;" onchange="this.form.submit()" {{ !$examId ? 'disabled' : '' }}>
                        <option value="">All</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ (string)$classId === (string)$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
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
                                    <th class="text-center" style="width: 90px;">Pos</th>
                                    <th style="min-width: 140px;">Exam No</th>
                                    <th style="min-width: 240px;">Full Name</th>
                                    <th style="width: 90px;">Sex</th>
                                    <th class="text-center" style="width: 120px;">Total</th>
                                    <th class="text-center" style="width: 120px;">Average</th>
                                    <th class="text-center" style="width: 90px;">Points</th>
                                    <th class="text-center" style="width: 90px;">Division</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($results as $r)
                                    <tr>
                                        <td class="px-4 text-muted">{{ $loop->iteration }}</td>
                                        <td class="text-center font-weight-bold">{{ $r->position ?? '-' }}</td>
                                        <td class="font-weight-bold">{{ $r->student?->registration_number ?? '-' }}</td>
                                        <td class="text-nowrap">{{ $r->student?->full_name ?? '-' }}</td>
                                        <td>{{ $r->student?->sex ?? '-' }}</td>
                                        <td class="text-center font-weight-bold">{{ number_format((float)$r->total_marks, 2) }}</td>
                                        <td class="text-center">{{ number_format((float)$r->average, 2) }}</td>
                                        <td class="text-center">{{ $r->total_points }}</td>
                                        <td class="text-center">{{ $r->division ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            <i class="fas fa-medal fa-3x mb-3 d-block opacity-50"></i>
                                            No ranking data found.
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
                    <p class="text-muted mb-0">Choose an exam to view ranking.</p>
                </div>
            </div>
        @endif
    </div>
@stop
