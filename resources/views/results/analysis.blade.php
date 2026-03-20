@extends('adminlte::page')

@section('title', 'Performance Analysis')

@section('content_header')
    <div class="container-fluid no-print">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-4">
                <h1 class="mb-0">Performance Analysis</h1>
                <small class="text-muted">Visual analytics of exam performance across subjects and divisions.</small>
            </div>
            <div class="col-sm-8">
                <form method="GET" action="{{ route('results.analysis') }}" class="d-flex justify-content-sm-end justify-content-start flex-wrap" style="gap: 12px;">
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
                    <i class="fas fa-chart-pie fa-4x text-muted mb-3 opacity-25"></i>
                    <h4 class="text-muted">Select an Exam to start analysis</h4>
                    <p class="text-muted">Choose from the filters above to generate visual reports.</p>
                </div>
            </div>
        @else
            {{-- Top Stats Cards --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 stat-card" style="border-left: 4px solid #4e73df; border-radius: 10px;">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $analysis['total'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 stat-card" style="border-left: 4px solid #1cc88a; border-radius: 10px;">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pass (Div I-IV)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ collect($analysis['division_counts'])->only(['I', 'II', 'III', 'IV'])->sum() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 stat-card" style="border-left: 4px solid #36b9cc; border-radius: 10px;">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Avg Score</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $analysis['avg_average'] }}%</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 stat-card" style="border-left: 4px solid #f6c23e; border-radius: 10px;">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Incomplete</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $analysis['total'] - $analysis['complete'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Division Distribution (Pie Chart) --}}
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                        <div class="card-header py-3 bg-white border-bottom-0">
                            <h6 class="m-0 font-weight-bold text-dark">Division Distribution</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height: 300px;">
                                <canvas id="divisionPieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Subject Performance (Bar Chart) --}}
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                        <div class="card-header py-3 bg-white border-bottom-0">
                            <h6 class="m-0 font-weight-bold text-dark">Subject Average Scores (%)</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height: 350px;">
                                <canvas id="subjectBarChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Summary Table --}}
            <div class="card shadow-sm border-0 mb-4 no-print" style="border-radius: 12px;">
                <div class="card-header py-3 bg-white border-bottom-0">
                    <h6 class="m-0 font-weight-bold text-dark">Subject-wise Data Table</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light text-uppercase font-weight-bold" style="font-size: 11px;">
                                <tr>
                                    <th class="px-4">Subject Name</th>
                                    <th class="text-center">Average Score</th>
                                    <th class="text-center">Students Sat</th>
                                    <th class="text-center">Grade Equivalent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($analysis['subject_performance'] as $sub)
                                    @php
                                        $avg = $sub['avg'];
                                        $grade = '-';
                                        if ($avg >= 75) $grade = 'A';
                                        elseif ($avg >= 65) $grade = 'B';
                                        elseif ($avg >= 45) $grade = 'C';
                                        elseif ($avg >= 30) $grade = 'D';
                                        else $grade = 'F';

                                        $badgeClass = match($grade) {
                                            'A' => 'success',
                                            'B' => 'primary',
                                            'C' => 'info',
                                            'D' => 'warning',
                                            'F' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <tr>
                                        <td class="px-4 font-weight-bold">{{ $sub['name'] }}</td>
                                        <td class="text-center h6 font-weight-bold">{{ $avg }}%</td>
                                        <td class="text-center text-muted">{{ $sub['count'] }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $badgeClass }} px-3" style="font-size: 11px;">{{ $grade }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted small">No subject data found for this selection.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@stop

@section('css')
<style>
    .stat-card {
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    @media print {
        .no-print { display: none !important; }
        .main-sidebar, .main-header { display: none !important; }
        .content-wrapper { margin-left: 0 !important; }
        .card { box-shadow: none !important; border: 1px solid #ddd !important; }
        .container-fluid { width: 100% !important; padding: 0 !important; }
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if($examId)
    // Division Pie Chart
    const divCtx = document.getElementById('divisionPieChart');
    new Chart(divCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($analysis['division_counts']->keys()) !!},
            datasets: [{
                data: {!! json_encode($analysis['division_counts']->values()) !!},
                backgroundColor: ['#28a745', '#007bff', '#17a2b8', '#ffc107', '#dc3545', '#6c757d'],
                borderWidth: 2
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Subject Performance Bar Chart
    const subCtx = document.getElementById('subjectBarChart');
    new Chart(subCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($analysis['subject_performance']->pluck('name')) !!},
            datasets: [{
                label: 'Average Score (%)',
                data: {!! json_encode($analysis['subject_performance']->pluck('avg')) !!},
                backgroundColor: '#4e73df',
                borderRadius: 5
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { display: false } }
        }
    });
    @endif
</script>
@stop
