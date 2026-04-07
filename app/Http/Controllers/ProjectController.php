<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $search = request('search');
        $projects = Project::when($search, function ($query, $search) {
                return $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->orderBy('display_order', 'asc')
            ->get();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|min:2|max:255',
            'slug' => 'required|min:3|max:255|unique:projects',
            'category' => 'required|max:100',
            'description' => 'required|min:10',
            'status' => 'required|in:Draft,Beta publik,Production,Archived',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
            'project_url' => 'required|max:500',
            'technologies' => 'nullable|string',
            'features' => 'nullable|string',
            'display_order' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        $technologies = $validated['technologies'] ? array_map('trim', explode(',', $validated['technologies'])) : [];
        $features = $validated['features'] ? array_map('trim', explode(',', $validated['features'])) : [];

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'project_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('projects'), $filename);
            $imagePath = 'projects/' . $filename;
        }

        Project::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'image' => $imagePath,
            'project_url' => $validated['project_url'],
            'technologies' => $technologies,
            'features' => $features,
            'display_order' => $validated['display_order'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('projects.index')->with('success', 'Project created successfully!');
    }

    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|min:2|max:255',
            'slug' => 'required|min:3|max:255|unique:projects,slug,' . $project->id,
            'category' => 'required|max:100',
            'description' => 'required|min:10',
            'status' => 'required|in:Draft,Beta publik,Production,Archived',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'project_url' => 'required|max:500',
            'technologies' => 'nullable|string',
            'features' => 'nullable|string',
            'display_order' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        $technologies = $validated['technologies'] ? array_map('trim', explode(',', $validated['technologies'])) : [];
        $features = $validated['features'] ? array_map('trim', explode(',', $validated['features'])) : [];

        $updateData = [
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'project_url' => $validated['project_url'],
            'technologies' => $technologies,
            'features' => $features,
            'display_order' => $validated['display_order'],
            'is_active' => $validated['is_active'] ?? $project->is_active,
        ];

        if ($request->hasFile('image')) {
            if ($project->image && file_exists(public_path($project->image))) {
                unlink(public_path($project->image));
            }
            
            $file = $request->file('image');
            $filename = 'project_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('projects'), $filename);
            $updateData['image'] = 'projects/' . $filename;
        } else {
            $updateData['image'] = $project->image;
        }

        $project->update($updateData);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully!');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully!');
    }
}