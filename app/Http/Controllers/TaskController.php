<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteTaskRequest;
use App\Http\Requests\GetTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    private TaskService $taskService;
    public function __construct()
    {
        $this->taskService = new TaskService(Auth::id());
    }

    public function create(StoreTaskRequest $request): JsonResponse
    {
        return $this->taskService->create($request);
    }

    public function update(UpdateTaskRequest $request): JsonResponse
    {
        return $this->taskService->update($request);
    }

    public function destroy(DeleteTaskRequest $request): JsonResponse
    {
        return $this->taskService->destroy($request);
    }

    public function get(GetTaskRequest $request): JsonResponse
    {
        return $this->taskService->get($request);
    }
}
