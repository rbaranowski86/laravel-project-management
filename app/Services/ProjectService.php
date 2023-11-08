<?php

namespace App\Services;

use App\Contracts\ProjectServiceInterface;
use App\Models\Project;
use App\Models\Task;

class ProjectService implements ProjectServiceInterface
{
    public function getAllProjects()
    {
        return Project::all();
    }

    public function createProject(array $data)
    {
        return Project::create($data);
    }

    public function getProjectById($id)
    {
        return Project::findOrFail($id);
    }

    public function updateProject($id, array $data)
    {
        $project = $this->getProjectById($id);
        $project->update($data);
        return $project;
    }

    public function deleteProject($id)
    {
        $project = $this->getProjectById($id);
        $project->delete();
    }
    public function getProjectStatistics()
    {
        $totalProjects = Project::count();
        $completedTasks = Task::where('status', 'completed')->count();
        $totalTasks = Task::count();

        return [
            'total_projects' => $totalProjects,
            'completed_tasks' => $completedTasks,
            'total_tasks' => $totalTasks,
        ];
    }
}
