@extends('adminlte::page')

@section('title', 'School Results')

@section('content_header')
    <div class="no-print">
        <div class="d-flex align-items-center justify-content-between" style="gap: 12px;">
            <div>
                <h1 class="mb-0">Exam Results Preview</h1>
                <small class="text-muted">Read-only preview of results layout for this exam. Marks and divisions will be filled once results are processed.</small>
            </div>
        </div>

        <div class="mt-2">
            <form method="GET" action="{{ route('results.school') }}" class="d-flex align-items-center" style="gap: 10px; flex-wrap: nowrap;">
            <label class="mb-0 text-muted" style="font-weight: 800; font-size: 12px;">EXAM</label>
            <select name="exam_id" class="form-control" style="min-width: 200px; border-radius: 6px; height: 38px;" onchange="this.form.submit()">
                <option value="">-- Choose Exam --</option>
                @foreach($exams as $exam)
                    <option value="{{ $exam->id }}" {{ (string)$examId === (string)$exam->id ? 'selected' : '' }}>
                        {{ $exam->title }} ({{ $exam->type?->name }})
                    </option>
                @endforeach
            </select>

            <label class="mb-0 text-muted" style="font-weight: 800; font-size: 12px;">CLASS</label>
            <select name="class_id" class="form-control" style="min-width: 150px; border-radius: 6px; height: 38px;" onchange="this.form.submit()" {{ !$examId ? 'disabled' : '' }}>
                <option value="">-- Choose Class --</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ (string)$classId === (string)$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>

            <label class="mb-0 text-muted" style="font-weight: 800; font-size: 12px;">VIEW</label>
            <select name="view" class="form-control" style="min-width: 140px; border-radius: 999px; height: 38px;" onchange="this.form.submit()" {{ (!$examId || !$classId) ? 'disabled' : '' }}>
                <option value="grades" {{ $viewBy === 'grades' ? 'selected' : '' }}>By Grades</option>
                <option value="marks" {{ $viewBy === 'marks' ? 'selected' : '' }}>By Marks</option>
            </select>
            </form>
        </div>

        <div class="mt-2 d-flex justify-content-end">
            <button type="button" class="btn btn-success" style="border-radius: 6px; height: 38px; white-space: nowrap;" onclick="window.print()">
                Print / Export
            </button>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        @if(!$examId)
            <div class="card shadow-sm" style="border-radius: 10px; border: none;">
                <div class="card-body text-center py-5">
                    <i class="fas fa-filter fa-4x text-muted mb-3 opacity-50"></i>
                    <h4 class="text-muted">Select an Exam</h4>
                    <p class="text-muted mb-0">Choose an exam to preview results.</p>
                </div>
            </div>
        @else
            @php
                $hasSelection = $examId && $classId;
            @endphp
            <div class="card shadow-sm" style="border-radius: 0; border: none; background-color: #b0c4de;">
                <div class="card-body" style="border-radius: 0; background: #b0c4de; padding: 20px;">
                    <div style="background: #b0c4de; padding: 10px;">
                        <div class="text-center" style="color: #000; font-family: 'Times New Roman', serif;">
                            <img src="{{ asset('emblem.png') }}" alt="Emblem" style="height: 70px; width: auto; display: block; margin: 0 auto 10px;" />
                            <div style="font-weight: bold; font-size: 14px; letter-spacing: 0.5px; text-transform: uppercase;">THE PRESIDENT'S OFFICE</div>
                            <div style="font-weight: bold; font-size: 14px; letter-spacing: 0.5px; text-transform: uppercase;">REGIONAL ADMINISTRATION AND LOCAL GOVERNMENT</div>
                            <div style="margin-top: 5px; font-weight: bold; font-size: 14px; letter-spacing: 0.5px; text-transform: uppercase;">EXAMINATION CENTRE RESULTS</div>
                            <div style="font-weight: bold; margin-top: 3px; font-size: 13px; text-transform: uppercase;">{{ optional($exams->firstWhere('id', $examId))->title }}</div>
                            <div style="font-weight: bold; margin-top: 5px; font-size: 13px; text-transform: uppercase;">{{ $activeSchool?->name ?? 'School Name' }}</div>
                            @if($classId)
                                <div style="font-weight: bold; margin-top: 2px; font-size: 13px; text-transform: uppercase;">
                                    {{ optional($classes->firstWhere('id', $classId))->name }}
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-center" style="margin-top: 20px;">
                            <table class="table table-bordered mb-0" style="max-width: 350px; background: #dcdcdc; border: 1px solid #000; line-height: 1;">
                                <thead>
                                    <tr>
                                        <th colspan="7" class="text-center" style="font-size: 11px; font-weight: bold; background: #cfd8a9; border: 1px solid #000; color: #000; padding: 2px 4px;">DIVISION PERFORMANCE SUMMARY</th>
                                    </tr>
                                    <tr class="text-center" style="font-size: 11px; font-weight: bold; background: #cfd8a9; color: #000;">
                                        <th style="width: 60px; border: 1px solid #000; padding: 2px 4px;">SEX</th>
                                        <th style="border: 1px solid #000; padding: 2px 4px;">I</th>
                                        <th style="border: 1px solid #000; padding: 2px 4px;">II</th>
                                        <th style="border: 1px solid #000; padding: 2px 4px;">III</th>
                                        <th style="border: 1px solid #000; padding: 2px 4px;">IV</th>
                                        <th style="border: 1px solid #000; padding: 2px 4px;">0</th>
                                        <th style="border: 1px solid #000; padding: 2px 4px;">TOT</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center" style="font-size: 11px; color: #000;">
                                    <tr>
                                        <td style="font-weight: bold; border: 1px solid #000; padding: 2px 4px;">F</td>
                                        <td style="border: 1px solid #000; padding: 2px 4px;">{{ $divisionSummary['F']['I'] }}</td>
                                        <td style="border: 1px solid #000; padding: 2px 4px;">{{ $divisionSummary['F']['II'] }}</td>
                                        <td style="border: 1px solid #000; padding: 2px 4px;">{{ $divisionSummary['F']['III'] }}</td>
                                        <td style="border: 1px solid #000; padding: 2px 4px;">{{ $divisionSummary['F']['IV'] }}</td>
                                        <td style="border: 1px solid #000; padding: 2px 4px;">{{ $divisionSummary['F']['0'] }}</td>
                                        <td style="font-weight: bold; border: 1px solid #000; padding: 2px 4px;">{{ $divisionSummary['F']['TOT'] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold; border: 1px solid #000; padding: 2px 4px;">M</td>
                                        <td style="border: 1px solid #000; padding: 2px 4px;">{{ $divisionSummary['M']['I'] }}</td>
                                        <td style="border: 1px solid #000; padding: 2px 4px;">{{ $divisionSummary['M']['II'] }}</td>
                                        <td style="border: 1px solid #000; padding: 2px 4px;">{{ $divisionSummary['M']['III'] }}</td>
                                        <td style="border: 1px solid #000; padding: 2px 4px;">{{ $divisionSummary['M']['IV'] }}</td>
                                        <td style="border: 1px solid #000; padding: 2px 4px;">{{ $divisionSummary['M']['0'] }}</td>
                                        <td style="font-weight: bold; border: 1px solid #000; padding: 2px 4px;">{{ $divisionSummary['M']['TOT'] }}</td>
                                    </tr>
                                    <tr style="background: #cfd8a9; font-weight: bold;">
                                        <td style="border: 1px solid #000; padding: 2px 4px;">T</td>
                                        <td style="border: 1px solid #000; padding: 2px 4px;">{{ $divisionSummary['T']['I'] }}</td>
                                        <td style="border: 1px solid #000; padding: 2px 4px;">{{ $divisionSummary['T']['II'] }}</td>
                                        <td style="border: 1px solid #000; padding: 2px 4px;">{{ $divisionSummary['T']['III'] }}</td>
                                        <td style="border: 1px solid #000; padding: 2px 4px;">{{ $divisionSummary['T']['IV'] }}</td>
                                        <td style="border: 1px solid #000; padding: 2px 4px;">{{ $divisionSummary['T']['0'] }}</td>
                                        <td style="border: 1px solid #000; padding: 2px 4px;">{{ $divisionSummary['T']['TOT'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        @php
                            $gradeOf = function ($score) {
                                if ($score === null || $score === '') return '-';
                                $s = (float) $score;
                                if ($s >= 75) return 'A';
                                if ($s >= 65) return 'B';
                                if ($s >= 45) return 'C';
                                if ($s >= 30) return 'D';
                                return 'F';
                            };
                        @endphp

                        <div class="table-responsive" style="background: #fdf5e6; border: 1px solid #000; margin-top: 20px; width: 100%;">
                            <table class="table table-bordered mb-0" style="font-size: 10px; color: #000; font-family: 'Times New Roman', serif; border: 1px solid #000; width: 100%;">
                                <thead style="background: #ffd700; font-size: 10px; border: 1px solid #000;">
                                    @if($viewBy === 'grades')
                                        <tr style="border: 1px solid #000;">
                                            <th rowspan="2" style="width: 85px; border: 1px solid #000; white-space: nowrap;">CNO</th>
                                            <th rowspan="2" style="border: 1px solid #000; width: auto;">FULL NAME</th>
                                            <th rowspan="2" style="width: 30px; border: 1px solid #000; white-space: nowrap;" class="text-center">SX</th>
                                            @foreach($subjects as $sub)
                                                <th colspan="2" class="text-center" style="width: 30px; border: 1px solid #000; vertical-align: bottom;">
                                                    <div class="rotate-text">{{ $sub->globalSubject->code ?? substr($sub->globalSubject->name, 0, 3) }}</div>
                                                </th>
                                            @endforeach
                                            <th rowspan="2" class="text-center" style="width: 40px; border: 1px solid #000; vertical-align: middle;">TOTAL</th>
                                            <th rowspan="2" class="text-center" style="width: 30px; border: 1px solid #000; vertical-align: middle;">GRD</th>
                                            <th rowspan="2" class="text-center" style="width: 35px; border: 1px solid #000; vertical-align: middle;">AVG</th>
                                            <th rowspan="2" class="text-center" style="width: 30px; border: 1px solid #000; vertical-align: middle;">DIV</th>
                                            <th rowspan="2" class="text-center" style="width: 30px; border: 1px solid #000; vertical-align: middle;">PTS</th>
                                        </tr>
                                        <tr style="border: 1px solid #000;">
                                            @foreach($subjects as $sub)
                                                <th class="text-center" style="border: 1px solid #000;">M</th>
                                                <th class="text-center" style="border: 1px solid #000;">G</th>
                                            @endforeach
                                        </tr>
                                    @else
                                        <tr style="border: 1px solid #000;">
                                            <th style="width: 80px; border: 1px solid #000; white-space: nowrap;">CNO</th>
                                            <th style="width: 130px; border: 1px solid #000; white-space: nowrap;">FULL NAME</th>
                                            <th style="width: 30px; border: 1px solid #000; white-space: nowrap;" class="text-center">SEX</th>
                                            <th style="border: 1px solid #000; white-space: nowrap;">DETAILED SUBJECTS &amp; MARKS</th>
                                            <th class="text-center" style="width: 40px; border: 1px solid #000; vertical-align: middle;">TOTAL</th>
                                            <th class="text-center" style="width: 30px; border: 1px solid #000; vertical-align: middle;">GRD</th>
                                            <th class="text-center" style="width: 35px; border: 1px solid #000; vertical-align: middle;">AVG</th>
                                            <th class="text-center" style="width: 30px; border: 1px solid #000; vertical-align: middle;">DIV</th>
                                            <th class="text-center" style="width: 30px; border: 1px solid #000; vertical-align: middle;">PTS</th>
                                        </tr>
                                    @endif
                                </thead>
                                <tbody>
                                    @if(!$hasSelection)
                                        <tr>
                                            <td colspan="{{ $viewBy === 'grades' ? (3 + ($subjects->count() * 2) + 5) : 10 }}" class="text-center py-5" style="border: 1px solid #000;">
                                                Chagua <strong>Darasa</strong> hapo juu ili kuona matokeo.
                                            </td>
                                        </tr>
                                    @else
                                        @forelse($students as $student)
                                        @php
                                            $res = $resultsByStudent->get($student->id);
                                            $total = $res ? (float) $res->total_marks : null;
                                            $avg = $res ? (float) $res->average : null;
                                            $div = $res ? $res->division : null;
                                            $pts = $res ? $res->total_points : null;
                                            $hasAny = $student->marks->count() > 0;
                                        @endphp
                                        <tr style="border: 1px solid #000;">
                                            <td style="font-weight: bold; border: 1px solid #000;">{{ $student->registration_number ?? '-' }}</td>
                                            <td class="text-nowrap" style="border: 1px solid #000;">{{ $student->full_name }}</td>
                                            <td class="text-center" style="border: 1px solid #000;">{{ $student->sex === 'Female' ? 'F' : 'M' }}</td>

                                            @if($viewBy === 'grades')
                                                @foreach($subjects as $sub)
                                                    @php
                                                        $mark = $student->marks->firstWhere('user_subject_id', $sub->id);
                                                        $score = $mark ? $mark->score : null;
                                                    @endphp
                                                    <td class="text-center" style="border: 1px solid #000;">{{ $score === null ? '-' : $score }}</td>
                                                    <td class="text-center" style="border: 1px solid #000;">{{ $score === null ? '-' : $gradeOf($score) }}</td>
                                                @endforeach
                                            @else
                                                <td style="border: 1px solid #000; font-size: 9.5px; white-space: nowrap;">
                                                    @php
                                                        $parts = [];
                                                        foreach ($subjects as $sub) {
                                                            $mark = $student->marks->firstWhere('user_subject_id', $sub->id);
                                                            $score = $mark ? $mark->score : null;
                                                            $code = $sub->globalSubject->code ?? substr($sub->globalSubject->name, 0, 3);
                                                            if ($score === null || $score === '') {
                                                                $parts[] = $code . ' -';
                                                            } else {
                                                                $parts[] = $code . ' ' . $score . '-' . $gradeOf($score);
                                                            }
                                                        }
                                                    @endphp
                                                    {{ implode(', ', $parts) }}
                                                </td>
                                            @endif

                                            <td class="text-center" style="font-weight: bold; border: 1px solid #000;">{{ $hasAny && $total !== null ? number_format($total, 0) : '-' }}</td>
                                            <td class="text-center" style="border: 1px solid #000;">{{ $hasAny && $avg !== null ? $gradeOf($avg) : '-' }}</td>
                                            <td class="text-center" style="border: 1px solid #000;">{{ $hasAny && $avg !== null ? number_format($avg, 1) : '-' }}</td>
                                            <td class="text-center" style="border: 1px solid #000;">{{ $div ?? ($hasAny ? 'INC' : '-') }}</td>
                                            <td class="text-center" style="border: 1px solid #000;">{{ $pts ?? ($hasAny ? 'INC' : '-') }}</td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ $viewBy === 'grades' ? (3 + ($subjects->count() * 2) + 5) : 10 }}" class="text-center py-5" style="border: 1px solid #000;">
                                                    No students found for this selection.
                                                </td>
                                            </tr>
                                        @endforelse
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@stop

@section('css')
<style>
    .rotate-text {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        white-space: nowrap;
        vertical-align: middle;
        text-align: center;
        height: 100px;
        padding: 5px 2px !important;
        margin: 0 auto;
        display: inline-block;
    }

    .table-bordered th, 
    .table-bordered td {
        border: 1px solid #000 !important;
    }

    @media print {
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .no-print,
        .main-sidebar,
        .main-header,
        .content-header,
        .control-sidebar,
        .main-footer {
            display: none !important;
        }

        body {
            background: #fff !important;
        }

        .content-wrapper {
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }

        .content {
            padding: 0 !important;
        }

        .card,
        .card-body {
            box-shadow: none !important;
            padding: 0 !important;
        }

        .table thead th,
        .table-bordered th,
        .table-bordered td {
            background-clip: padding-box;
            border: 1px solid #000 !important;
        }

        .table-responsive {
            overflow: visible !important;
            width: 100% !important;
        }

        table {
            width: 100% !important;
            border-collapse: collapse !important;
            page-break-inside: auto;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        th, td {
            padding: 2px 3px !important;
            border: 1px solid #000 !important;
        }

        .table { font-size: 8px !important; }
        .rotate-text { height: 60px; }
    }
</style>
@stop
