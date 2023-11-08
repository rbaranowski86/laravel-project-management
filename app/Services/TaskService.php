<?php

namespace App\Services;

use App\Contracts\TaskServiceInterface;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TaskService implements TaskServiceInterface
{
    public function getAllTasks()
    {
        return Task::all();
    }

    public function createTask(array $data)
    {
        return Task::create($data);
    }

    public function getTaskById($id)
    {
        return Task::findOrFail($id);
    }

    public function updateTask($id, array $data)
    {
        $task = $this->getTaskById($id);
        $task->update($data);
        return $task;
    }

    public function deleteTask($id)
    {
        $task = $this->getTaskById($id);
        $task->delete();
    }

    public function assignTask($taskId, $userId = null)
    {
        $task = $this->getTaskById($taskId);
        $userId = $userId ?? Auth::id();
        $user = User::findOrFail($userId);
        $task->user_id = $user->id;
        $task->save();
        return $task;
    }
}
