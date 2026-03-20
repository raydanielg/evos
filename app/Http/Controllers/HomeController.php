<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\School;
use App\Models\Student;
use App\Models\ExamResult;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function getActiveSchoolId()
    {
        return session('active_school_id');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $schoolId = $this->getActiveSchoolId();
        
        $stats = [
            'total_schools' => School::where('user_id', auth()->id())->count(),
            'total_students' => 0,
            'total_exams' => 0,
            'avg_performance' => 0,
            'recent_results' => collect(),
        ];

        if ($schoolId) {
            $stats['total_students'] = Student::where('school_id', $schoolId)->count();
            $stats['total_exams'] = Exam::where('school_id', $schoolId)->count();
            
            $results = ExamResult::whereHas('student', function($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })->get();

            if ($results->count() > 0) {
                $stats['avg_performance'] = round($results->avg('average'), 1);
            }

            $stats['recent_results'] = ExamResult::whereHas('student', function($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })->with(['student', 'exam'])
              ->latest()
              ->limit(5)
              ->get();
        }

        return view('home', compact('stats'));
    }
}
