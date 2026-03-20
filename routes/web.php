<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/auth');
});

Route::get('/auth', function () {
    return view('landing');
})->name('landing');

Route::get('/api/districts/{region}', function ($regionId) {
    return \App\Models\District::where('region_id', $regionId)->get();
});

Route::get('/sitemap.xml', function () {
    return response()->view('sitemap')->header('Content-Type', 'text/xml');
});

Auth::routes();

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/auth');
})->name('logout.get');

Route::middleware('auth')->group(function () {
    Route::resource('schools', App\Http\Controllers\SchoolController::class);

    Route::resource('students', App\Http\Controllers\StudentController::class);

    Route::get('students-import', [App\Http\Controllers\StudentController::class, 'importForm'])->name('students.import.form');
    Route::get('students-import-template', [App\Http\Controllers\StudentController::class, 'downloadImportTemplate'])->name('students.import.template');
    Route::post('students-import-preview', [App\Http\Controllers\StudentController::class, 'importPreview'])->name('students.import.preview');
    Route::post('students-import-confirm', [App\Http\Controllers\StudentController::class, 'importConfirm'])->name('students.import.confirm');

    Route::get('students-profile', [App\Http\Controllers\StudentController::class, 'profile'])->name('students.profile');

    Route::get('students-transfer', [App\Http\Controllers\StudentController::class, 'transferForm'])->name('students.transfer.form');
    Route::post('students-transfer-preview', [App\Http\Controllers\StudentController::class, 'transferPreview'])->name('students.transfer.preview');
    Route::post('students-transfer-progress', [App\Http\Controllers\StudentController::class, 'transferProgress'])->name('students.transfer.progress');
    Route::post('students/{student}/quick-transfer', [App\Http\Controllers\StudentController::class, 'quickTransfer'])->name('students.quick-transfer');

    Route::get('students-print', [App\Http\Controllers\StudentController::class, 'print'])->name('students.print');
    Route::post('students-assign-numbers', [App\Http\Controllers\StudentController::class, 'assignNumbers'])->name('students.assignNumbers');
    Route::post('students-reassign-numbers', [App\Http\Controllers\StudentController::class, 'reassignNumbers'])->name('students.reassignNumbers');

    // Subject Management Routes
    Route::get('subjects-manage', [App\Http\Controllers\SubjectController::class, 'manageMySubjects'])->name('subjects.manage');
    Route::post('subjects-manage', [App\Http\Controllers\SubjectController::class, 'updateMySubjects'])->name('subjects.update-my-subjects');
    Route::get('subjects-assign-class', [App\Http\Controllers\SubjectController::class, 'assignToClassForm'])->name('subjects.assign-class');
    Route::post('subjects-assign-class', [App\Http\Controllers\SubjectController::class, 'updateClassAssignments'])->name('subjects.update-class-assignments');
    // Exam Management Routes
    Route::resource('exams', App\Http\Controllers\ExamController::class);

    // Mark Management Routes
    Route::get('marks-entry', [App\Http\Controllers\MarkController::class, 'entryForm'])->name('marks.entry');
    Route::post('marks-store', [App\Http\Controllers\MarkController::class, 'store'])->name('marks.store');
    Route::post('marks-store-single', [App\Http\Controllers\MarkController::class, 'storeSingle'])->name('marks.store-single');
    Route::post('marks-toggle-lock', [App\Http\Controllers\MarkController::class, 'toggleLock'])->name('marks.toggle-lock');

    // Results
    Route::get('results', [App\Http\Controllers\MarkController::class, 'results'])->name('results.index');
    Route::get('results/class', [App\Http\Controllers\ResultsController::class, 'classResults'])->name('results.class');
    Route::get('results/school', [App\Http\Controllers\ResultsController::class, 'schoolResults'])->name('results.school');
    Route::get('results/ranking', [App\Http\Controllers\ResultsController::class, 'positionRanking'])->name('results.ranking');
    Route::get('results/analysis', [App\Http\Controllers\ResultsController::class, 'performanceAnalysis'])->name('results.analysis');
    Route::get('results/student-analysis', [App\Http\Controllers\ResultsController::class, 'studentAnalysis'])->name('results.student-analysis');

    // Settings
    Route::get('settings/profile', [App\Http\Controllers\SettingsController::class, 'profile'])->name('settings.profile');
    Route::post('settings/profile', [App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::get('settings/school', [App\Http\Controllers\SettingsController::class, 'school'])->name('settings.school');
    Route::post('settings/school', [App\Http\Controllers\SettingsController::class, 'updateSchool'])->name('settings.school.update');
    Route::get('settings/grading', [App\Http\Controllers\SettingsController::class, 'grading'])->name('settings.grading');
    Route::get('settings/sms', [App\Http\Controllers\SettingsController::class, 'sms'])->name('settings.sms');

    Route::post('active-school/{school}', function (\App\Models\School $school) {
        abort_unless($school->user_id === auth()->id(), 403);
        session(['active_school_id' => $school->id]);
        return back()->with('success', 'Active school updated.');
    })->name('active-school.set');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
