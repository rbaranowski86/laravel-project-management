<?php

namespace App\Contracts;

interface ProjectServiceInterface
{
    public function getAllProjects();
    public function createProject(array $data);
    public function getProjectById($id);
    public function updateProject($id, array $data);
    public function deleteProject($id);
    public function getProjectStatistics();
}
