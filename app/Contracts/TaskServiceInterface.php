<?php

namespace App\Contracts;

interface TaskServiceInterface
{
    public function getAllTasks();
    public function createTask(array $data);
    public function getTaskById($id);
    public function updateTask($id, array $data);
    public function deleteTask($id);
    public function assignTask($taskId, $userId);
}
