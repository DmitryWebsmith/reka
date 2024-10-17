<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    protected TaskService $taskService;

    public function __construct(Request $request)
    {
        $this->taskService = $request->attributes->get('taskService');
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
