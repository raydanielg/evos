<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function getActiveSchoolId()
    {
        return session('active_school_id');
    }

    public function profile()
    {
        $user = auth()->user();
        return view('settings.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->filled('new_password')) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
            }
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function school()
    {
        $schoolId = $this->getActiveSchoolId();
        abort_unless($schoolId, 403, 'Select a school first.');
        
        $school = School::findOrFail($schoolId);
        return view('settings.school', compact('school'));
    }

    public function updateSchool(Request $request)
    {
        $schoolId = $this->getActiveSchoolId();
        abort_unless($schoolId, 403);
        
        $school = School::findOrFail($schoolId);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'head_phone' => 'nullable|string|max:20',
            'reg_number' => 'nullable|string|max:50',
        ]);

        $school->update($validated);

        return back()->with('success', 'School settings updated successfully.');
    }

    public function grading()
    {
        return view('settings.grading');
    }

    public function sms()
    {
        return view('settings.sms');
    }
}
