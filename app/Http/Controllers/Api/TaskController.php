<?php

namespace App\Http\Controllers\Api;

use App\Contracts\TaskServiceInterface;
use App\Http\Resources\TaskResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskServiceInterface $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        $tasks = $this->taskService->getAllTasks();
        return TaskResource::collection($tasks);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:completed,in-progress,pending',
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
        ]);

        $task = $this->taskService->createTask($validatedData);
        return new TaskResource($task);
    }

    public function show($id)
    {
        $task = $this->taskService->getTaskById($id);
        return new TaskResource($task);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'status' => 'sometimes|in:completed,in-progress,pending',
            'user_id' => 'sometimes|exists:users,id',
            'project_id' => 'sometimes|exists:projects,id',
        ]);

        $task = $this->taskService->updateTask($id, $validatedData);
        return new TaskResource($task);
    }

    public function destroy($id)
    {
        $this->taskService->deleteTask($id);
        return response()->json(null, 204);
    }

    public function assign(Request $request, $taskId)
    {
        $userId = $request->input('user_id');
        $task = $this->taskService->assignTask($taskId, $userId);
        return response()->json([
            'message' => 'Task assigned successfully.',
            'task' => new TaskResource($task)
        ]);
    }
}
