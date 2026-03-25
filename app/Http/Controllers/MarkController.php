<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\School;
use App\Models\Student;
use App\Models\Mark;
use App\Models\ExamResult;
use App\Models\ExamLock;
use App\Models\UserSubject;
use App\Models\ClassSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function getActiveSchoolId()
    {
        return session('active_school_id');
    }

    public function entryForm(Request $request)
    {
        $schoolId = $this->getActiveSchoolId();
        abort_unless($schoolId, 403, 'Select a school first.');

        $exams = Exam::where('school_id', $schoolId)->latest()->get();
        $examId = $request->integer('exam_id');
        $classId = $request->integer('class_id');
        $subjectId = $request->integer('subject_id');

        $classes = collect();
        $subjects = collect();
        $students = collect();
        $examLock = null;

        if ($examId) {
            $exam = Exam::with('examClasses.schoolClass')->findOrFail($examId);
            $classes = $exam->examClasses->pluck('schoolClass');

            // Get lock status (available as long as an exam is selected)
            $examLock = ExamLock::where('exam_id', $examId)
                ->where('school_id', $schoolId)
                ->first();
        }

        if ($classId) {
            $subjects = ClassSubject::where('school_id', $schoolId)
                ->where('class_id', $classId)
                ->with('userSubject.globalSubject')
                ->get()
                ->pluck('userSubject')
                ->sortBy(fn($us) => $us->globalSubject->name);
        }

        if ($examId && $classId) {
            $query = Student::where('school_id', $schoolId)
                ->where('class_id', $classId)
                ->orderByRaw("CASE WHEN sex = 'Female' THEN 0 ELSE 1 END")
                ->orderBy('first_name')
                ->orderBy('middle_name')
                ->orderBy('last_name');

            $query->with(['marks' => function ($q) use ($examId) {
                $q->where('exam_id', $examId);
            }, 'examResults' => function ($q) use ($examId) {
                $q->where('exam_id', $examId);
            }]);

            $students = $query->get();

            if (!$subjectId) {
                $this->recalculateResults($examId, $classId);
                // Refresh students to get new results
                $students = $query->get();
            }
        }

        return view('marks.entry', compact('exams', 'classes', 'subjects', 'students', 'examId', 'classId', 'subjectId', 'examLock'));
    }

    public function downloadMarksTemplate(Request $request)
    {
        $schoolId = $this->getActiveSchoolId();
        abort_unless($schoolId, 403);

        $examId = $request->integer('exam_id');
        $classId = $request->integer('class_id');
        $subjectId = $request->integer('subject_id');

        abort_unless($examId && $classId && $subjectId, 422, 'Missing required parameters.');

        $exam = Exam::findOrFail($examId);
        $schoolClass = SchoolClass::findOrFail($classId);
        $userSubject = UserSubject::with('globalSubject')->findOrFail($subjectId);

        $students = Student::where('school_id', $schoolId)
            ->where('class_id', $classId)
            ->orderByRaw("CASE WHEN sex = 'Female' THEN 0 ELSE 1 END")
            ->orderBy('first_name')
            ->orderBy('middle_name')
            ->orderBy('last_name')
            ->get();

        $filename = "marks_template_{$schoolClass->name}_{$userSubject->globalSubject->name}.csv";

        return response()->streamDownload(function () use ($students) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Index Number', 'Full Name', 'Score']);
            foreach ($students as $student) {
                fputcsv($out, [
                    $student->registration_number,
                    $student->full_name,
                    ''
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function importPreview(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|integer|exists:exams,id',
            'class_id' => 'required|integer|exists:school_classes,id',
            'subject_id' => 'required|integer|exists:user_subjects,id',
            'file' => 'required|file',
        ]);

        $path = $request->file('file')->getRealPath();
        $handle = fopen($path, 'r');
        abort_unless($handle !== false, 422);

        $header = fgetcsv($handle);
        $rows = [];
        $errors = [];

        $rowNo = 1;
        while (($data = fgetcsv($handle)) !== false) {
            $rowNo++;
            if (empty($data) || (count($data) === 1 && trim((string)$data[0]) === '')) continue;

            $indexNo = trim((string)($data[0] ?? ''));
            $fullName = trim((string)($data[1] ?? ''));
            $score = trim((string)($data[2] ?? ''));

            $student = null;
            if ($indexNo) {
                $student = Student::where('school_id', $this->getActiveSchoolId())
                    ->where('registration_number', $indexNo)
                    ->first();
            }

            if (!$student && $fullName) {
                // Try matching by name if index no fails
                $students = Student::where('school_id', $this->getActiveSchoolId())
                    ->where('class_id', $validated['class_id'])
                    ->get();
                
                foreach ($students as $s) {
                    if (strcasecmp($s->full_name, $fullName) === 0) {
                        $student = $s;
                        break;
                    }
                }
            }

            $rows[] = [
                'student_id' => $student?->id,
                'index_no' => $indexNo,
                'full_name' => $fullName ?: ($student?->full_name ?? 'Unknown'),
                'score' => $score,
                'is_valid' => $student !== null && is_numeric($score) && $score >= 0 && $score <= 100,
                'error' => $student === null ? 'Student not found' : (!is_numeric($score) ? 'Invalid score' : ($score < 0 || $score > 100 ? 'Score out of range' : null))
            ];
        }
        fclose($handle);

        $exam = Exam::findOrFail($validated['exam_id']);
        $schoolClass = SchoolClass::findOrFail($validated['class_id']);
        $subject = UserSubject::with('globalSubject')->findOrFail($validated['subject_id']);

        return view('marks.import_preview', compact('rows', 'exam', 'schoolClass', 'subject'));
    }

    public function importConfirm(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|integer|exists:exams,id',
            'subject_id' => 'required|integer|exists:user_subjects,id',
            'class_id' => 'required|integer|exists:school_classes,id',
            'rows' => 'required|array',
        ]);

        $count = 0;
        DB::transaction(function () use ($validated, &$count) {
            foreach ($validated['rows'] as $r) {
                if (empty($r['student_id']) || !isset($r['score']) || $r['score'] === '') continue;

                Mark::updateOrCreate(
                    [
                        'exam_id' => $validated['exam_id'],
                        'student_id' => $r['student_id'],
                        'user_subject_id' => $validated['subject_id'],
                    ],
                    [
                        'class_id' => $validated['class_id'],
                        'score' => (int)$r['score'],
                    ]
                );
                $count++;
            }
        });

        return response()->json([
            'success' => true,
            'message' => "Successfully imported {$count} marks.",
            'count' => $count
        ]);
    }

    public function results(Request $request)
    {
        $schoolId = $this->getActiveSchoolId();
        abort_unless($schoolId, 403, 'Select a school first.');

        $exams = Exam::where('school_id', $schoolId)->latest()->get();
        $examId = $request->integer('exam_id');
        $classId = $request->integer('class_id');
        $studentId = $request->integer('student_id');

        $classes = collect();
        $students = collect();
        $selectedStudent = null;
        $results = collect();
        $summary = [
            'total' => 0,
            'complete' => 0,
            'incomplete' => 0,
            'divisions' => [],
        ];

        if ($examId) {
            $exam = Exam::with('examClasses.schoolClass')->findOrFail($examId);
            $classes = $exam->examClasses->pluck('schoolClass');
        }

        if ($classId) {
            $students = Student::where('school_id', $schoolId)
                ->where('class_id', $classId)
                ->orderBy('first_name')
                ->get();
        }

        if ($examId && $classId) {
            $this->recalculateResults($examId, $classId);

            $results = ExamResult::where('exam_id', $examId)
                ->where('class_id', $classId)
                ->with('student')
                ->orderByRaw('CASE WHEN position IS NULL THEN 1 ELSE 0 END')
                ->orderBy('position')
                ->orderByDesc('total_marks')
                ->get();

            $summary['total'] = $results->count();
            $summary['complete'] = $results->where('is_complete', true)->count();
            $summary['incomplete'] = $summary['total'] - $summary['complete'];

            $summary['divisions'] = $results
                ->pluck('division')
                ->map(fn ($d) => $d ?? '-')
                ->countBy()
                ->sortKeys();

            if ($studentId) {
                $selectedStudent = Student::with([
                    'marks' => function ($q) use ($examId) {
                        $q->where('exam_id', $examId)->with('userSubject.globalSubject');
                    },
                    'examResults' => function ($q) use ($examId) {
                        $q->where('exam_id', $examId);
                    },
                    'schoolClass'
                ])->find($studentId);
            }
        }

        $activeSchool = School::find($schoolId);

        return view('results.index', compact('activeSchool', 'exams', 'classes', 'students', 'examId', 'classId', 'studentId', 'selectedStudent', 'results', 'summary'));
    }

    public function toggleLock(Request $request)
    {
        $schoolId = $this->getActiveSchoolId();
        abort_unless($schoolId, 403);

        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'pin' => 'required|digits:4',
            'action' => 'required|in:lock,unlock'
        ]);

        $lock = ExamLock::where('exam_id', $validated['exam_id'])
            ->where('school_id', $schoolId)
            ->first();

        if ($validated['action'] === 'lock') {
            if ($lock) {
                $lock->update([
                    'pin' => $validated['pin'],
                    'is_locked' => true
                ]);
            } else {
                ExamLock::create([
                    'exam_id' => $validated['exam_id'],
                    'school_id' => $schoolId,
                    'pin' => $validated['pin'],
                    'is_locked' => true
                ]);
            }
            return response()->json(['success' => true, 'message' => 'Exam locked successfully.']);
        } else {
            // Unlock action
            if (!$lock) {
                return response()->json(['success' => false, 'message' => 'No lock found for this exam.'], 404);
            }

            if ($lock->pin !== $validated['pin']) {
                return response()->json(['success' => false, 'message' => 'Invalid PIN.'], 403);
            }

            $lock->update(['is_locked' => false]);
            return response()->json(['success' => true, 'message' => 'Exam unlocked successfully.']);
        }
    }

    private function recalculateResults($examId, $classId)
    {
        $subjectsCount = ClassSubject::where('class_id', $classId)->count();
        $students = Student::where('class_id', $classId)
            ->with(['marks' => function ($q) use ($examId) {
                $q->where('exam_id', $examId);
            }])->get();

        DB::transaction(function () use ($students, $examId, $classId, $subjectsCount) {
            // Sort students to assign positions sequentially (no joint positions)
            // Priority: Higher Total Marks -> First Name
            $sortedStudents = $students->sortByDesc(function ($student) {
                return (float)$student->marks->sum('score');
            })->sortBy(function($student) {
                return $student->first_name;
            }, SORT_NATURAL, false)->values();

            $rank = 1;
            foreach ($sortedStudents as $student) {
                $marksFound = $student->marks->count();
                $total = (int)$student->marks->sum('score');
                $isComplete = ($marksFound >= 7 && $subjectsCount >= 7);
                $avg = $marksFound > 0 ? (int)round($total / $marksFound) : 0;

                // Points calculation
                $scores = $student->marks->pluck('score')->toArray();
                rsort($scores);
                $allPoints = array_map(function ($s) {
                    if ($s >= 75) return 1;
                    elseif ($s >= 65) return 2;
                    elseif ($s >= 45) return 3;
                    elseif ($s >= 30) return 4;
                    else return 5;
                }, $scores);
                $best7Points = array_slice($allPoints, 0, 7);
                $points = array_sum($best7Points);

                $division = '-';
                if ($marksFound >= 7) {
                    if ($points <= 17) $division = 'I';
                    elseif ($points <= 21) $division = 'II';
                    elseif ($points <= 25) $division = 'III';
                    elseif ($points <= 33) $division = 'IV';
                    else $division = '0';
                } elseif ($marksFound > 0) {
                    $division = 'INC';
                }

                ExamResult::updateOrCreate(
                    [
                        'exam_id' => $examId,
                        'student_id' => $student->id
                    ],
                    [
                        'school_id' => $student->school_id,
                        'class_id' => $classId,
                        'total_marks' => $total,
                        'average' => $avg,
                        'total_points' => $points,
                        'division' => $division,
                        'is_complete' => ($marksFound >= 7),
                        'position' => ($marksFound >= 7) ? $rank++ : null,
                    ]
                );
            }
        });
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'class_id' => 'required|exists:school_classes,id',
            'user_subject_id' => 'nullable|exists:user_subjects,id',
            'marks' => 'required|array',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['marks'] as $studentId => $markData) {
                // If subject is selected, markData is the score
                if (isset($validated['user_subject_id'])) {
                    $score = $markData;
                    if ($score === null || $score === '') continue;

                    Mark::updateOrCreate(
                        [
                            'exam_id' => $validated['exam_id'],
                            'student_id' => $studentId,
                            'user_subject_id' => $validated['user_subject_id'],
                        ],
                        [
                            'class_id' => $validated['class_id'],
                            'score' => $score,
                        ]
                    );
                } else {
                    // All subjects view: markData is an array of [subject_id => score]
                    if (!is_array($markData)) continue;
                    
                    foreach ($markData as $subId => $score) {
                        if ($score === null || $score === '') continue;

                        Mark::updateOrCreate(
                            [
                                'exam_id' => $validated['exam_id'],
                                'student_id' => $studentId,
                                'user_subject_id' => $subId,
                            ],
                            [
                                'class_id' => $validated['class_id'],
                                'score' => $score,
                            ]
                        );
                    }
                }
            }
        });

        return back()->with('success', 'Marks saved successfully.');
    }

    public function storeSingle(Request $request)
    {
        $schoolId = $this->getActiveSchoolId();
        abort_unless($schoolId, 403);

        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:school_classes,id',
            'user_subject_id' => 'required|exists:user_subjects,id',
            'score' => 'nullable|numeric|min:0|max:100',
        ]);

        // Check if locked
        $isLocked = ExamLock::where('exam_id', $validated['exam_id'])
            ->where('school_id', $schoolId)
            ->where('is_locked', true)
            ->exists();

        if ($isLocked) {
            return response()->json(['success' => false, 'message' => 'This exam is locked.'], 403);
        }

        $mark = Mark::updateOrCreate(
            [
                'exam_id' => $validated['exam_id'],
                'student_id' => $validated['student_id'],
                'user_subject_id' => $validated['user_subject_id'],
            ],
            [
                'class_id' => $validated['class_id'],
                'score' => (int)$validated['score'],
            ]
        );

        // Recalculate results for the entire class to update positions
        $this->recalculateResults($validated['exam_id'], $validated['class_id']);

        // Fetch updated results for all students in the class to return to UI
        $results = ExamResult::where('exam_id', $validated['exam_id'])
            ->where('class_id', $validated['class_id'])
            ->get()
            ->mapWithKeys(function ($res) {
                return [$res->student_id => [
                    'total_marks' => (int)$res->total_marks,
                    'average' => (int)$res->average,
                    'total_points' => (int)$res->total_points,
                    'division' => $res->division,
                    'is_complete' => (bool)$res->is_complete,
                    'position' => $res->position,
                    'grade' => $this->calculateGradeFromAvg($res->average)
                ]];
            });

        return response()->json([
            'success' => true,
            'message' => 'Mark saved and positions updated.',
            'results' => $results
        ]);
    }

    private function calculateGradeFromAvg($avg)
    {
        if ($avg >= 75) return 'A';
        if ($avg >= 65) return 'B';
        if ($avg >= 45) return 'C';
        if ($avg >= 30) return 'D';
        return 'F';
    }
}
