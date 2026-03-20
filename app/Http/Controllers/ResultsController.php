<?php

namespace App\Http\Controllers;

use App\Models\ClassSubject;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\School;
use App\Models\Student;
use Illuminate\Http\Request;

class ResultsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function getActiveSchoolId()
    {
        return session('active_school_id');
    }

    public function classResults(Request $request)
    {
        $schoolId = $this->getActiveSchoolId();
        abort_unless($schoolId, 403, 'Select a school first.');

        $exams = Exam::where('school_id', $schoolId)->latest()->get();
        $examId = $request->integer('exam_id');

        $rows = collect();

        if ($examId) {
            $exam = Exam::with('examClasses.schoolClass')->findOrFail($examId);
            $classes = $exam->examClasses->pluck('schoolClass');

            $rows = $classes->map(function ($c) use ($examId) {
                $results = ExamResult::where('exam_id', $examId)
                    ->where('class_id', $c->id)
                    ->get();

                $total = $results->count();
                $complete = $results->where('is_complete', true)->count();
                $avgTotal = $results->count() ? round((float) $results->avg('total_marks'), 2) : 0;
                $avgAverage = $results->where('is_complete', true)->count() ? round((float) $results->where('is_complete', true)->avg('average'), 2) : 0;

                $divisions = $results
                    ->pluck('division')
                    ->map(fn ($d) => $d ?? '-')
                    ->countBy()
                    ->sortKeys();

                return (object) [
                    'class' => $c,
                    'total' => $total,
                    'complete' => $complete,
                    'incomplete' => $total - $complete,
                    'avg_total' => $avgTotal,
                    'avg_average' => $avgAverage,
                    'divisions' => $divisions,
                ];
            })->sortBy(fn ($r) => $r->class->name)->values();
        }

        return view('results.class', compact('exams', 'examId', 'rows'));
    }

    public function schoolResults(Request $request)
    {
        $schoolId = $this->getActiveSchoolId();
        abort_unless($schoolId, 403, 'Select a school first.');

        $activeSchool = School::find($schoolId);

        $exams = Exam::where('school_id', $schoolId)->latest()->get();
        $examId = $request->integer('exam_id');
        $classId = $request->integer('class_id');
        $viewBy = $request->string('view', 'grades')->toString();
        if (!in_array($viewBy, ['grades', 'marks'], true)) {
            $viewBy = 'grades';
        }

        $classes = collect();
        $subjects = collect();
        $students = collect();
        $resultsByStudent = collect();
        $divisionSummary = [
            'F' => ['I' => 0, 'II' => 0, 'III' => 0, 'IV' => 0, '0' => 0, 'TOT' => 0],
            'M' => ['I' => 0, 'II' => 0, 'III' => 0, 'IV' => 0, '0' => 0, 'TOT' => 0],
            'T' => ['I' => 0, 'II' => 0, 'III' => 0, 'IV' => 0, '0' => 0, 'TOT' => 0],
        ];

        if ($examId) {
            $exam = Exam::with('examClasses.schoolClass')->findOrFail($examId);
            $classes = $exam->examClasses->pluck('schoolClass');
        }

        if ($examId && $classId) {
            $classIds = collect([$classId]);

            $subjects = ClassSubject::where('school_id', $schoolId)
                ->whereIn('class_id', $classIds)
                ->with('userSubject.globalSubject')
                ->get()
                ->pluck('userSubject')
                ->unique('id')
                ->sortBy(fn ($us) => $us->globalSubject->name)
                ->values();

            $students = Student::where('school_id', $schoolId)
                ->whereIn('class_id', $classIds)
                ->with([
                    'marks' => function ($q) use ($examId) {
                        $q->where('exam_id', $examId);
                    },
                    'examResults' => function ($q) use ($examId) {
                        $q->where('exam_id', $examId);
                    },
                ])
                ->orderByRaw("CASE WHEN sex = 'Female' THEN 0 ELSE 1 END")
                ->orderBy('first_name')
                ->orderBy('middle_name')
                ->orderBy('last_name')
                ->get();

            $results = ExamResult::where('exam_id', $examId)
                ->whereIn('class_id', $classIds)
                ->with('student')
                ->get();

            $resultsByStudent = $results->keyBy('student_id');

            foreach ($results as $res) {
                if (!$res->division) continue;
                if (!$res->student) continue;

                $sexKey = ($res->student->sex === 'Female') ? 'F' : 'M';
                $divKey = (string) $res->division;
                if (!array_key_exists($divKey, $divisionSummary[$sexKey])) {
                    continue;
                }

                $divisionSummary[$sexKey][$divKey] += 1;
                $divisionSummary[$sexKey]['TOT'] += 1;

                $divisionSummary['T'][$divKey] += 1;
                $divisionSummary['T']['TOT'] += 1;
            }
        }

        return view('results.school', compact('activeSchool', 'exams', 'examId', 'classes', 'classId', 'viewBy', 'subjects', 'students', 'resultsByStudent', 'divisionSummary'));
    }

    public function positionRanking(Request $request)
    {
        $schoolId = $this->getActiveSchoolId();
        abort_unless($schoolId, 403, 'Select a school first.');

        $exams = Exam::where('school_id', $schoolId)->latest()->get();
        $examId = $request->integer('exam_id');
        $classId = $request->integer('class_id');

        $classes = collect();
        $results = collect();

        if ($examId) {
            $exam = Exam::with('examClasses.schoolClass')->findOrFail($examId);
            $classes = $exam->examClasses->pluck('schoolClass');
        }

        if ($examId) {
            $query = ExamResult::where('exam_id', $examId)
                ->with('student');

            if ($classId) {
                $query->where('class_id', $classId);
            }

            $results = $query
                ->where('is_complete', true)
                ->orderByRaw('CASE WHEN position IS NULL THEN 1 ELSE 0 END')
                ->orderBy('position')
                ->orderByDesc('total_marks')
                ->get();
        }

        return view('results.ranking', compact('exams', 'classes', 'examId', 'classId', 'results'));
    }

    public function performanceAnalysis(Request $request)
    {
        $schoolId = $this->getActiveSchoolId();
        abort_unless($schoolId, 403, 'Select a school first.');

        $exams = Exam::where('school_id', $schoolId)->latest()->get();
        $examId = $request->integer('exam_id');
        $classId = $request->integer('class_id');

        $classes = collect();
        $analysis = [
            'total' => 0,
            'complete' => 0,
            'avg_total' => 0,
            'avg_average' => 0,
            'division_counts' => collect(),
            'sex_counts' => collect(),
            'subject_performance' => collect(),
        ];

        if ($examId) {
            $exam = Exam::with('examClasses.schoolClass')->findOrFail($examId);
            $classes = $exam->examClasses->pluck('schoolClass');

            $query = ExamResult::where('exam_id', $examId)->with('student');
            if ($classId) {
                $query->where('class_id', $classId);
            }

            $results = $query->get();

            $analysis['total'] = $results->count();
            $analysis['complete'] = $results->where('is_complete', true)->count();
            $analysis['avg_total'] = $analysis['total'] ? round((float) $results->avg('total_marks'), 2) : 0;
            $analysis['avg_average'] = $analysis['complete'] ? round((float) $results->where('is_complete', true)->avg('average'), 2) : 0;

            $analysis['division_counts'] = $results
                ->pluck('division')
                ->map(fn ($d) => $d ?? 'INC')
                ->countBy()
                ->sortKeys();

            $analysis['sex_counts'] = $results
                ->map(fn ($r) => $r->student?->sex ?? '-')
                ->countBy()
                ->sortKeys();

            // Calculate subject-wise performance
            if ($classId) {
                $subjects = ClassSubject::where('class_id', $classId)
                    ->with('userSubject.globalSubject')
                    ->get()
                    ->pluck('userSubject');

                $subjectData = [];
                foreach ($subjects as $sub) {
                    $marks = \App\Models\Mark::where('exam_id', $examId)
                        ->where('user_subject_id', $sub->id)
                        ->where('class_id', $classId)
                        ->pluck('score');

                    if ($marks->count() > 0) {
                        $avg = round((float)$marks->avg(), 2);
                        $subjectData[] = [
                            'name' => $sub->globalSubject->name,
                            'avg' => $avg,
                            'count' => $marks->count()
                        ];
                    }
                }
                $analysis['subject_performance'] = collect($subjectData);
            }
        }

        return view('results.analysis', compact('exams', 'classes', 'examId', 'classId', 'analysis'));
    }

    public function studentAnalysis(Request $request)
    {
        $schoolId = $this->getActiveSchoolId();
        abort_unless($schoolId, 403, 'Select a school first.');

        $exams = Exam::where('school_id', $schoolId)->latest()->get();
        $examId = $request->integer('exam_id');
        $classId = $request->integer('class_id');

        $classes = collect();
        $topBest = collect();
        $topLosers = collect();

        if ($examId) {
            $exam = Exam::with('examClasses.schoolClass')->findOrFail($examId);
            $classes = $exam->examClasses->pluck('schoolClass');

            $query = ExamResult::where('exam_id', $examId)
                ->where('is_complete', true)
                ->whereNotNull('division')
                ->where('division', '!=', 'INC')
                ->with('student');

            if ($classId) {
                $query->where('class_id', $classId);
            }

            // Top 10 Best: Order by position (ASC) then total marks (DESC)
            $topBest = (clone $query)->orderBy('position')
                ->orderByDesc('total_marks')
                ->limit(10)
                ->get();

            // Top 10 Losers: Order by division DESC (0, IV, III...) then total marks ASC
            // We want the absolute bottom ones, excluding INC.
            $topLosers = (clone $query)->orderByDesc('division')
                ->orderBy('total_marks')
                ->limit(10)
                ->get();
        }

        return view('results.student-analysis', compact('exams', 'classes', 'examId', 'classId', 'topBest', 'topLosers'));
    }
}
