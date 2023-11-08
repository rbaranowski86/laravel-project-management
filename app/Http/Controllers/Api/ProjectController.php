<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Contracts\ProjectServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Project;
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

    public function store(StoreProjectRequest $request)
    {
        $project = $this->projectService->createProject($request->validated());
        return new ProjectResource($project);
    }

    public function show($id)
    {
        $project = $this->projectService->getProjectById($id);
        return new ProjectResource($project);
    }

    public function update(UpdateProjectRequest $request, $id)
    {
        $project = $this->projectService->updateProject($id, $request->validated());
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
