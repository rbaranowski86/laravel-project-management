<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProjectResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return ProjectResource::collection($projects);

    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:completed,in-progress,pending',
            'deadline' => 'required|date',
        ]);

        $project = Project::create($validatedData);
        return new ProjectResource($project);
    }

    public function show(Project $project)
    {
        return new ProjectResource($project);
    }

    public function update(Request $request, Project $project)
    {
        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'status' => 'sometimes|in:completed,in-progress,pending',
            'deadline' => 'sometimes|date',
        ]);

        $project->update($validatedData);
        return new ProjectResource($project);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(null, 204);
    }

    public function statistics()
    {
        $totalProjects = Project::count();
        $completedTasks = Task::where('status', 'completed')->count();
        $totalTasks = Task::count();

        return response()->json([
            'total_projects' => $totalProjects,
            'completed_tasks' => $completedTasks,
            'total_tasks' => $totalTasks,
        ]);
    }
}
