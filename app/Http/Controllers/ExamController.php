<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamType;
use App\Models\ExamClass;
use App\Models\ExamParticipant;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function getActiveSchoolId()
    {
        return session('active_school_id');
    }

    public function index()
    {
        $schoolId = $this->getActiveSchoolId();
        abort_unless($schoolId, 403, 'Select a school first.');

        $exams = Exam::where('school_id', $schoolId)->latest()->get();
        return view('exams.index', compact('exams'));
    }

    public function create()
    {
        $schoolId = $this->getActiveSchoolId();
        abort_unless($schoolId, 403, 'Select a school first.');

        $classes = SchoolClass::orderBy('sort_order')->get();
        $types = ExamType::orderBy('name')->get();
        
        return view('exams.create', compact('classes', 'types'));
    }

    public function store(Request $request)
    {
        $schoolId = $this->getActiveSchoolId();
        abort_unless($schoolId, 403);

        $validated = $request->validate([
            'exam_type_id' => 'required|exists:exam_types,id',
            'title' => 'required|string|max:255',
            'exam_date' => 'required|date',
            'class_ids' => 'required|array',
            'class_ids.*' => 'exists:school_classes,id',
        ]);

        DB::transaction(function () use ($schoolId, $validated) {
            $exam = Exam::create([
                'school_id' => $schoolId,
                'exam_type_id' => $validated['exam_type_id'],
                'title' => $validated['title'],
                'exam_date' => $validated['exam_date'],
                'status' => 'created',
            ]);

            foreach ($validated['class_ids'] as $classId) {
                ExamClass::create([
                    'exam_id' => $exam->id,
                    'class_id' => $classId,
                ]);

                // Auto-populate participants from this class
                $students = Student::where('school_id', $schoolId)
                    ->where('class_id', $classId)
                    ->get();

                foreach ($students as $student) {
                    ExamParticipant::create([
                        'exam_id' => $exam->id,
                        'student_id' => $student->id,
                        'class_id' => $classId,
                    ]);
                }
            }
        });

        return redirect()->route('exams.index')->with('success', 'Exam created and participants registered successfully.');
    }

    public function show(Exam $exam)
    {
        abort_unless($exam->school_id == $this->getActiveSchoolId(), 403);
        $exam->load(['examClasses.schoolClass', 'participants.student', 'participants.schoolClass']);
        
        return view('exams.show', compact('exam'));
    }

    public function destroy(Exam $exam)
    {
        abort_unless($exam->school_id == $this->getActiveSchoolId(), 403);
        $exam->delete();

        return back()->with('success', 'Exam deleted successfully.');
    }
}
