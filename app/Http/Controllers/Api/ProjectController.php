<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Project::where('is_active', true)
                ->whereNull('deleted_at')
                ->orderBy('display_order', 'asc')
                ->orderBy('created_at', 'desc');
            
            if ($request->has('category')) {
                $query->where('category', $request->category);
            }
            
            $projects = $query->get();
            
            $projects->transform(function ($project) {
                $project->image_url = asset($project->image);
                return $project;
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Projects retrieved successfully',
                'data' => $projects,
                'count' => count($projects)
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving projects',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function show($slug)
    {
        try {
            $project = Project::where('slug', $slug)
                ->where('is_active', true)
                ->whereNull('deleted_at')
                ->firstOrFail();
            
            $project->image_url = asset($project->image);
            
            return response()->json([
                'success' => true,
                'message' => 'Project retrieved successfully',
                'data' => $project
            ], 200);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving project',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
