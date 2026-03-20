<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schools = School::where('user_id', auth()->id())->latest()->get();
        return view('schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('schools.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reg_number' => 'required|unique:schools',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:schools',
            'head_phone' => 'required|string|max:20',
            'category' => 'required|in:Government,Private',
        ]);

        $validated['user_id'] = auth()->id();
        School::create($validated);

        return redirect()->route('schools.index')->with('success', 'School added successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school)
    {
        if ($school->user_id !== auth()->id()) {
            abort(403);
        }
        return view('schools.edit', compact('school'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, School $school)
    {
        if ($school->user_id !== auth()->id()) {
            abort(403);
        }
        $validated = $request->validate([
            'reg_number' => 'required|unique:schools,reg_number,' . $school->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:schools,email,' . $school->id,
            'head_phone' => 'required|string|max:20',
            'category' => 'required|in:Government,Private',
        ]);

        $school->update($validated);

        return redirect()->route('schools.index')->with('success', 'School updated successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(School $school)
    {
        if ($school->user_id !== auth()->id()) {
            abort(403);
        }
        return view('schools.show', compact('school'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        if ($school->user_id !== auth()->id()) {
            abort(403);
        }
        $school->delete();
        return redirect()->route('schools.index')->with('success', 'School deleted successfully!');
    }
}
