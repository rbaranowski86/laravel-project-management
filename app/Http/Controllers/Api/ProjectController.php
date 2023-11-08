<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProjectResource;
use App\Contracts\ProjectServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectServiceInterface $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index()
    {
        $projects = $this->projectService->getAllProjects();
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

        $project = $this->projectService->createProject($validatedData);
        return new ProjectResource($project);
    }

    public function show($id)
    {
        $project = $this->projectService->getProjectById($id);
        return new ProjectResource($project);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'status' => 'sometimes|in:completed,in-progress,pending',
            'deadline' => 'sometimes|date',
        ]);

        $project = $this->projectService->updateProject($id, $validatedData);
        return new ProjectResource($project);
    }

    public function destroy($id)
    {
        $this->projectService->deleteProject($id);
        return response()->json(null, 204);
    }

    public function statistics()
    {
        $statistics = $this->projectService->getProjectStatistics();
        return response()->json($statistics);
    }
}
