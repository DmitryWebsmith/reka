<?php

namespace App\Http\Controllers;

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

    public function index(): JsonResponse
    {
        return new JsonResponse(['task' => $this->taskService->index()]);
    }

    public function show(int $id): JsonResponse
    {
        return $this->taskService->show($id);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        return $this->taskService->store($request);
    }

    public function update(int $id, UpdateTaskRequest $request): JsonResponse
    {
        return $this->taskService->update($id, $request);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->taskService->destroy($id);
    }
}
