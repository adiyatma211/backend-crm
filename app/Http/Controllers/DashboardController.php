<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Testimonial;
use App\Models\Client;
use App\Models\Stat;

class DashboardController extends Controller
{
    public function index()
    {
        $projectCount = Project::count();
        $testimonialCount = Testimonial::count();
        $clientCount = Client::count();
        $statsCount = Stat::count();

        return view('dashboard', compact('projectCount', 'testimonialCount', 'clientCount', 'statsCount'));
    }

    public function profile()
    {
        return view('profile');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|min:2|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => bcrypt($validated['password'])]);
        }

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }

    public function analytics()
    {
        return view('analytics');
    }
}
