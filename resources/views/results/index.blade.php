@extends('adminlte::page')

@section('title', 'Results')

@section('content_header')
    <div class="container-fluid no-print">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-4">
                <h1 class="mb-0">Student Results</h1>
                <small class="text-muted">Select an exam, class, and student to view the report form.</small>
            </div>
            <div class="col-sm-8">
                <form method="GET" action="{{ route('results.index') }}" class="d-flex justify-content-sm-end justify-content-start flex-wrap" style="gap: 8px;">
                    <div style="min-width: 180px;">
                        <label class="mb-0" style="font-weight: 700; font-size: 11px; text-transform: uppercase; color: #666;">Exam</label>
                        <select name="exam_id" class="form-control form-control-sm" style="border-radius: 4px;" onchange="this.form.submit()">
                            <option value="">-- Choose Exam --</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}" {{ (string)$examId === (string)$exam->id ? 'selected' : '' }}>
                                    {{ $exam->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="min-width: 140px;">
                        <label class="mb-0" style="font-weight: 700; font-size: 11px; text-transform: uppercase; color: #666;">Class</label>
                        <select name="class_id" class="form-control form-control-sm" style="border-radius: 4px;" onchange="this.form.submit()" {{ !$examId ? 'disabled' : '' }}>
                            <option value="">-- Choose Class --</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ (string)$classId === (string)$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="min-width: 200px;">
                        <label class="mb-0" style="font-weight: 700; font-size: 11px; text-transform: uppercase; color: #666;">Student</label>
                        <select name="student_id" class="form-control form-control-sm" style="border-radius: 4px;" onchange="this.form.submit()" {{ !$classId ? 'disabled' : '' }}>
                            <option value="">-- Choose Student --</option>
                            @foreach($students as $s)
                                <option value="{{ $s->id }}" {{ (string)$studentId === (string)$s->id ? 'selected' : '' }}>{{ $s->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex align-items-end">
                        <button type="button" class="btn btn-sm btn-success" onclick="window.print()" {{ !$selectedStudent ? 'disabled' : '' }}>
                            <i class="fas fa-print mr-1"></i> Print Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        @if(!$selectedStudent)
            <div class="card shadow-sm no-print" style="border-radius: 10px; border: none;">
                <div class="card-body text-center py-5">
                    <i class="fas fa-id-card fa-4x text-muted mb-3 opacity-50"></i>
                    <h4 class="text-muted">Select a Student to View Report</h4>
                    <p class="text-muted">Choose Exam, Class, and then Student from the filters above.</p>
                </div>
            </div>
        @else
            {{-- Report Card UI based on uploaded image --}}
            <div class="report-card-container">
                <div class="report-header text-center">
                    <img src="{{ asset('emblem.png') }}" alt="Emblem" style="height: 60px; margin-bottom: 5px;">
                    <h5 class="mb-0" style="font-weight: 800;">HALMASHAURI YA MANISPAA</h5>
                    <h5 class="mb-0" style="font-weight: 800;">{{ strtoupper($activeSchool->name ?? 'ANGELINA MABULA SECONDARY SCHOOL') }}</h5>
                    <p class="mb-0" style="font-size: 11px;">Simu: {{ $activeSchool->head_phone ?? '+255621286609' }}, Email: {{ strtoupper($activeSchool->email ?? 'INFO@AMSS.AC.TZ') }}</p>
                    <h6 class="mt-2 mb-3" style="font-weight: 800; border-bottom: 2px solid #000; display: inline-block; padding-bottom: 2px;">
                        TAARIFA YA MAENDELEO YA MWANAFUNZI (MWAKA WA {{ date('Y') }})
                    </h6>
                </div>

                <div class="row student-info-row no-gutters">
                    <div class="col-7 pr-1">
                        <div class="info-cell">
                            <small class="label">JINA:</small>
                            <div class="value">{{ strtoupper($selectedStudent->full_name) }}</div>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="info-cell">
                            <small class="label">KIDATO:</small>
                            <div class="value">{{ strtoupper($selectedStudent->schoolClass->name ?? '-') }}</div>
                        </div>
                    </div>
                </div>

                <div class="row exam-info-row no-gutters mt-1">
                    <div class="col-4 pr-1">
                        <div class="info-cell">
                            <small class="label">NUSU MUHULA / MUHULA:</small>
                            <div class="value">{{ strtoupper(optional($exams->firstWhere('id', $examId))->title) }}</div>
                        </div>
                    </div>
                    <div class="col-4 pr-1">
                        <div class="info-cell">
                            <small class="label">MWAKA:</small>
                            <div class="value">{{ date('Y') }}</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="info-cell">
                            <small class="label">TAREHE:</small>
                            <div class="value">{{ date('j/n/Y') }}</div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    {{-- Academic Results Table --}}
                    <div class="col-7 pr-1">
                        <div class="section-title">A. MATOKEO YAKE YA MITIHANI</div>
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>MASOMO</th>
                                    <th class="text-center">ALAMA</th>
                                    <th class="text-center">WASTANI</th>
                                    <th class="text-center">GREDI</th>
                                    <th>COMMENT</th>
                                </tr>
                            </thead>
                            <tbody>
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
                                    $commentOf = function ($grade) {
                                        switch($grade) {
                                            case 'A': return 'Vizuri Sana (Excellent)';
                                            case 'B': return 'Vizuri (Very Good)';
                                            case 'C': return 'Vizuri (Good)';
                                            case 'D': return 'Inaridhisha (Satisfactory)';
                                            case 'F': return 'Feli (Fail)';
                                            default: return '-';
                                        }
                                    };
                                    $totalMarks = 0;
                                    $count = 0;
                                @endphp
                                @foreach($selectedStudent->marks as $mark)
                                    <tr>
                                        <td>{{ $mark->userSubject->globalSubject->name }}</td>
                                        <td class="text-center">{{ (int)$mark->score }}</td>
                                        <td class="text-center">{{ (int)$mark->score }}</td>
                                        <td class="text-center">{{ $gradeOf($mark->score) }}</td>
                                        <td>{{ $commentOf($gradeOf($mark->score)) }}</td>
                                    </tr>
                                    @php
                                        $totalMarks += $mark->score;
                                        $count++;
                                    @endphp
                                @endforeach
                                <tr class="footer-row">
                                    <td class="text-right"><strong>JUMLA</strong></td>
                                    <td class="text-center"><strong>{{ (int)$totalMarks }}</strong></td>
                                    <td class="text-center"><strong>{{ $count > 0 ? number_format($totalMarks/$count, 2) : '0.00' }}</strong></td>
                                    <td class="text-center"><strong>{{ $selectedStudent->examResults->first()->division ?? '-' }}</strong></td>
                                    <td><strong>Points: {{ $selectedStudent->examResults->first()->total_points ?? '-' }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                        <small class="text-muted" style="font-size: 8px;">NB: INC - Hakukamilisha; ABS - Hakufanya mtihani.</small>
                    </div>

                    {{-- Behavior & Development --}}
                    <div class="col-5 pl-1">
                        <div class="section-title">B. UPIMAJI WA TABIA NA MAENDELEO</div>
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>TABIA</th>
                                    <th class="text-center">GREDI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $traits = [
                                        '901: Bidii ya Kazi' => 'A',
                                        '902: Mahudhurio Darasani' => 'A',
                                        '903: Kutunza mali za shule' => 'A',
                                        '904: Utii kazini/darasani' => 'A',
                                        '905: Ushirikiano na ushirikiano' => 'A',
                                        '906: Heshima kwa wote' => 'A',
                                        '907: Kumudu uongozi' => 'B',
                                        '908: Adabu' => 'A',
                                        '909: Kujua usafi binafsi' => 'A',
                                        '910: Kukubali ushauri' => 'B',
                                        '911: Kuthamini kazi' => 'A',
                                        '912: Kujituma' => 'A',
                                        '913: Kushiriki michezo' => 'A',
                                        '914: Sifa nzuri ya Ushauri' => 'B',
                                    ];
                                @endphp
                                @foreach($traits as $trait => $grd)
                                    <tr>
                                        <td>{{ $trait }}</td>
                                        <td class="text-center">{{ $grd }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-2" style="font-size: 10px;">
                    Maelezo ya viwango vya ufaulu: A=75-100, B=65-74, C=45-64, D=30-44, F=0-29.
                </div>

                <div class="summary-box mt-1">
                    Amekuwa wa <strong>{{ $selectedStudent->examResults->first()->position ?? '-' }}</strong> kati ya wanafunzi <strong>{{ $summary['total'] }}</strong> wa darasa lake. Pointi: <strong>{{ $selectedStudent->examResults->first()->total_points ?? '-' }}</strong>. Division: <strong>{{ $selectedStudent->examResults->first()->division ?? '-' }}</strong>.
                </div>

                <div class="row mt-1">
                    <div class="col-6 pr-1">
                        <div style="font-size: 9.5px; font-weight: bold;">Maoni ya Mwalimu wa Darasa:</div>
                        <div class="comment-line">Umefanya vizuri. Ongeza juhudi zaidi katika masomo ya sayansi.</div>
                        <div class="mt-1" style="font-size: 9.5px;">
                            Jina/Sahihi: ................................................. Tarehe: <strong>{{ date('d/m/Y') }}</strong>
                        </div>
                    </div>
                    <div class="col-6 pl-1 border-left">
                        <div style="font-size: 9.5px; font-weight: bold;">Maoni ya Mkuu wa shule:</div>
                        <div class="comment-line">Hongera kwa matokeo mazuri. Endelea na moyo huo wa kujituma.</div>
                        <div class="mt-1" style="font-size: 9.5px;">
                            Sahihi/Muhuri: <strong>{{ strtoupper($activeSchool->headmaster_name ?? 'JOAS MALIMA MAUGO') }}</strong> Tarehe: <strong>{{ date('d/m/Y') }}</strong>
                        </div>
                    </div>
                </div>

                <hr style="border-top: 1px dashed #000;" class="mt-3">

                <div class="mt-2">
                    <div style="font-size: 11px; font-weight: bold;">C: KUFUNGA/KUFUNGUA SHULE</div>
                    <div style="font-size: 10px;">
                        Shule imefungwa leo Tarehe ........................................... na itafunguliwa rasmi Tarehe ...........................................
                    </div>
                </div>

                <div class="mt-2" style="font-size: 10px;">
                    Kata hapa rudisha maoni yako shule siku ya kufungua shule
                </div>

                <div class="mt-1">
                    <div style="font-size: 10px; font-weight: bold;">Maoni ya mzazi wa:</div>
                    <div class="comment-line" style="border-bottom: 1px solid #ccc; min-height: 30px;"></div>
                    <div class="d-flex justify-content-between mt-2" style="font-size: 9.5px;">
                        <div>JINA LA MZAZI/MLEZI: .................................................</div>
                        <div>SAINI: .................................................</div>
                        <div>TAREHE: .................................................</div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@stop

@section('css')
<style>
    .report-card-container {
        background: #fff;
        padding: 20px;
        color: #000;
        font-family: 'Times New Roman', Times, serif;
        max-width: 900px;
        margin: 0 auto;
        border: 1px solid #eee;
    }
    .report-header img { display: block; margin: 0 auto; }
    .info-cell {
        border: 1px solid #6c757d;
        padding: 2px 8px;
        background: #f8f9fa;
        min-height: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .info-cell .label {
        color: #444;
        font-size: 9px;
        margin-bottom: -2px;
        font-weight: 500;
    }
    .info-cell .value {
        font-weight: 800;
        font-size: 12px;
    }
    .section-title {
        background: #dee2e6;
        border: 1px solid #000;
        padding: 2px 8px;
        font-weight: bold;
        font-size: 11px;
        margin-top: 5px;
    }
    .report-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #000;
        font-size: 10.5px;
    }
    .report-table th, .report-table td {
        border: 1px solid #000;
        padding: 3px 6px;
    }
    .report-table th { background: #f8f9fa; }
    .footer-row td { background: #f0f0f0; }
    .summary-box {
        border: 1px solid #000;
        padding: 4px 8px;
        font-size: 11px;
        background: #f8f9fa;
    }
    .comment-line {
        border: 1px solid #000;
        min-height: 35px;
        padding: 3px 6px;
        font-style: italic;
        font-size: 10px;
        background: #fafafa;
    }

    @media print {
        @page { size: A4 portrait; margin: 10mm; }
        .no-print, .main-sidebar, .main-header, .content-header, .main-footer { display: none !important; }
        .content-wrapper { margin: 0 !important; padding: 0 !important; background: #fff !important; }
        .report-card-container { border: none; width: 100%; max-width: none; padding: 0; }
        .info-box, .section-title, .report-table, .report-table th, .report-table td, .summary-box, .comment-line {
            border-color: #000 !important;
        }
    }
</style>
@stop
