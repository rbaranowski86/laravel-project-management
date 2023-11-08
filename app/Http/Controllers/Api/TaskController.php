<?php

namespace App\Http\Controllers\Api;

use App\Contracts\TaskServiceInterface;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
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

    public function store(StoreTaskRequest $request)
    {
        $validatedData = $request->validated();
        $task = $this->taskService->createTask($validatedData);
        return new TaskResource($task);
    }

    public function show($id)
    {
        $task = $this->taskService->getTaskById($id);
        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $validatedData = $request->validated();
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
