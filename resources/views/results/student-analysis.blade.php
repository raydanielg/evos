@extends('adminlte::page')

@section('title', 'Student Analysis')

@section('content_header')
    <div class="container-fluid no-print">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-4">
                <h1 class="mb-0">Student Analysis</h1>
                <small class="text-muted">Top 10 Performers vs Bottom 10 (excluding Incomplete).</small>
            </div>
            <div class="col-sm-8">
                <form method="GET" action="{{ route('results.student-analysis') }}" class="d-flex justify-content-sm-end justify-content-start flex-wrap" style="gap: 12px;">
                    <div style="min-width: 200px;">
                        <label class="mb-0" style="font-weight: 700; font-size: 11px; text-transform: uppercase; color: #666;">Select Exam</label>
                        <select name="exam_id" class="form-control form-control-sm shadow-sm" style="border-radius: 6px; height: 35px;" onchange="this.form.submit()">
                            <option value="">-- Choose Exam --</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}" {{ (string)$examId === (string)$exam->id ? 'selected' : '' }}>
                                    {{ $exam->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="min-width: 160px;">
                        <label class="mb-0" style="font-weight: 700; font-size: 11px; text-transform: uppercase; color: #666;">Select Class</label>
                        <select name="class_id" class="form-control form-control-sm shadow-sm" style="border-radius: 6px; height: 35px;" onchange="this.form.submit()" {{ !$examId ? 'disabled' : '' }}>
                            <option value="">-- All Classes --</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ (string)$classId === (string)$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex align-items-end">
                        <button type="button" class="btn btn-sm btn-outline-primary shadow-sm" style="height: 35px; border-radius: 6px;" onclick="window.print()" {{ !$examId ? 'disabled' : '' }}>
                            <i class="fas fa-print mr-1"></i> Print Analysis
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        @if(!$examId)
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-body text-center py-5">
                    <i class="fas fa-user-graduate fa-4x text-muted mb-3 opacity-25"></i>
                    <h4 class="text-muted">Select an Exam to start student analysis</h4>
                    <p class="text-muted">Identify your top performers and students who need more support.</p>
                </div>
            </div>
        @else
            <div class="row">
                {{-- Top 10 Best Students --}}
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; border-top: 4px solid #28a745 !important;">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="m-0 font-weight-bold text-success">
                                <i class="fas fa-trophy mr-2"></i> TOP 10 BEST STUDENTS
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light text-uppercase small font-weight-bold">
                                        <tr>
                                            <th class="px-3" style="width: 50px;">Pos</th>
                                            <th>Student Name</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Div</th>
                                            <th class="text-center">Pts</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topBest as $res)
                                            <tr>
                                                <td class="px-3">
                                                    <span class="badge badge-success" style="width: 25px; height: 25px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        {{ $res->position }}
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold">{{ $res->student->full_name }}</td>
                                                <td class="text-center">{{ (int)$res->total_marks }}</td>
                                                <td class="text-center">
                                                    <span class="badge badge-outline-success border border-success text-success px-2">{{ $res->division }}</span>
                                                </td>
                                                <td class="text-center font-weight-bold">{{ $res->total_points }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted small">No data available for top performers.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Top 10 Losers (Bottom Performers) --}}
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; border-top: 4px solid #dc3545 !important;">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="m-0 font-weight-bold text-danger">
                                <i class="fas fa-chart-line-down mr-2"></i> TOP 10 BOTTOM PERFORMERS
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light text-uppercase small font-weight-bold">
                                        <tr>
                                            <th class="px-3" style="width: 50px;">Pos</th>
                                            <th>Student Name</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Div</th>
                                            <th class="text-center">Pts</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topLosers as $res)
                                            <tr>
                                                <td class="px-3">
                                                    <span class="badge badge-danger" style="width: 25px; height: 25px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        {{ $res->position }}
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold">{{ $res->student->full_name }}</td>
                                                <td class="text-center">{{ (int)$res->total_marks }}</td>
                                                <td class="text-center">
                                                    <span class="badge badge-outline-danger border border-danger text-danger px-2">{{ $res->division }}</span>
                                                </td>
                                                <td class="text-center font-weight-bold">{{ $res->total_points }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted small">No data available for bottom performers.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Summary Analysis Chart --}}
            <div class="card shadow-sm border-0 mb-4 no-print" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="m-0 font-weight-bold text-dark">Comparison Chart (Total Marks)</h6>
                </div>
                <div class="card-body">
                    <div style="height: 300px; position: relative;">
                        <canvas id="comparisonChart"></canvas>
                    </div>
                </div>
            </div>
        @endif
    </div>
@stop

@section('css')
<style>
    .badge-outline-success { background: transparent; color: #28a745; }
    .badge-outline-danger { background: transparent; color: #dc3545; }
    
    @media print {
        .no-print { display: none !important; }
        .main-sidebar, .main-header { display: none !important; }
        .content-wrapper { margin-left: 0 !important; }
        .card { box-shadow: none !important; border: 1px solid #eee !important; page-break-inside: avoid; }
        .row { display: flex; flex-wrap: nowrap; }
        .col-md-6 { width: 50% !important; flex: 0 0 50%; max-width: 50%; }
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if($examId && $topBest->count() > 0)
    const ctx = document.getElementById('comparisonChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                ...{!! json_encode($topBest->pluck('student.full_name')->map(fn($n) => explode(' ', $n)[0])) !!},
                '---',
                ...{!! json_encode($topLosers->pluck('student.full_name')->map(fn($n) => explode(' ', $n)[0])) !!}
            ],
            datasets: [{
                label: 'Total Marks',
                data: [
                    ...{!! json_encode($topBest->pluck('total_marks')) !!},
                    0,
                    ...{!! json_encode($topLosers->pluck('total_marks')) !!}
                ],
                backgroundColor: [
                    ...Array({{ $topBest->count() }}).fill('#28a745'),
                    '#fff',
                    ...Array({{ $topLosers->count() }}).fill('#dc3545')
                ],
                borderRadius: 5
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
    @endif
</script>
@stop
