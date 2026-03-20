<?php

namespace App\Http\Controllers;

use App\Models\GlobalSubject;
use App\Models\UserSubject;
use App\Models\SchoolClass;
use App\Models\ClassSubject;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function getActiveSchoolId(): ?int
    {
        return session('active_school_id');
    }

    public function manageMySubjects()
    {
        $globalSubjects = GlobalSubject::orderBy('name')->get();
        $mySubjectIds = auth()->user()->userSubjects()->pluck('global_subject_id')->toArray();

        return view('subjects.manage_my_subjects', compact('globalSubjects', 'mySubjectIds'));
    }

    public function updateMySubjects(Request $request)
    {
        $validated = $request->validate([
            'subject_ids' => 'array',
            'subject_ids.*' => 'exists:global_subjects,id',
        ]);

        $subjectIds = $validated['subject_ids'] ?? [];

        DB::transaction(function () use ($subjectIds) {
            // Remove subjects not in the list
            auth()->user()->userSubjects()->whereNotIn('global_subject_id', $subjectIds)->delete();

            // Add new subjects
            foreach ($subjectIds as $id) {
                auth()->user()->userSubjects()->firstOrCreate(['global_subject_id' => $id]);
            }
        });

        return back()->with('success', 'My subjects updated successfully.');
    }

    public function assignToClassForm()
    {
        $schoolId = $this->getActiveSchoolId();
        abort_unless($schoolId, 403, 'Select an active school first.');

        $school = School::findOrFail($schoolId);
        $classes = SchoolClass::orderBy('sort_order')->get();
        $mySubjects = auth()->user()->userSubjects()->with('globalSubject')->get()
            ->sortBy(fn($us) => $us->globalSubject->name);

        // Get existing assignments
        $assignments = ClassSubject::where('school_id', $schoolId)->get()
            ->groupBy('class_id')
            ->map(fn($group) => $group->pluck('user_subject_id')->toArray());

        return view('subjects.assign_to_class', compact('school', 'classes', 'mySubjects', 'assignments'));
    }

    public function updateClassAssignments(Request $request)
    {
        $schoolId = $this->getActiveSchoolId();
        abort_unless($schoolId, 403);

        $validated = $request->validate([
            'assignments' => 'array',
            'assignments.*' => 'array', // class_id => [user_subject_ids]
        ]);

        $assignments = $validated['assignments'] ?? [];

        DB::transaction(function () use ($schoolId, $assignments) {
            // Simple approach: delete all for this school and re-insert
            // Optimization: only delete classes being updated if needed
            ClassSubject::where('school_id', $schoolId)->delete();

            foreach ($assignments as $classId => $userSubjectIds) {
                foreach ($userSubjectIds as $usId) {
                    ClassSubject::create([
                        'school_id' => $schoolId,
                        'class_id' => $classId,
                        'user_subject_id' => $usId,
                    ]);
                }
            }
        });

        return back()->with('success', 'Class subject assignments updated successfully.');
    }
}
