<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('display_order', 'asc')->get();
        return view('testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('testimonials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'text' => 'required|min:10',
            'author' => 'required|min:2|max:255',
            'title' => 'required|max:255',
            'initials' => 'required|min:2|max:10',
            'rating' => 'required|integer|min:1|max:5',
            'display_order' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        Testimonial::create([
            'text' => $validated['text'],
            'author' => $validated['author'],
            'title' => $validated['title'],
            'initials' => $validated['initials'],
            'rating' => $validated['rating'],
            'display_order' => $validated['display_order'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('testimonials.index')->with('success', 'Testimonial created successfully!');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'text' => 'required|min:10',
            'author' => 'required|min:2|max:255',
            'title' => 'required|max:255',
            'initials' => 'required|min:2|max:10',
            'rating' => 'required|integer|min:1|max:5',
            'display_order' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        $testimonial->update([
            'text' => $validated['text'],
            'author' => $validated['author'],
            'title' => $validated['title'],
            'initials' => $validated['initials'],
            'rating' => $validated['rating'],
            'display_order' => $validated['display_order'],
            'is_active' => $validated['is_active'] ?? $testimonial->is_active,
        ]);

        return redirect()->route('testimonials.index')->with('success', 'Testimonial updated successfully!');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return redirect()->route('testimonials.index')->with('success', 'Testimonial deleted successfully!');
    }
}
