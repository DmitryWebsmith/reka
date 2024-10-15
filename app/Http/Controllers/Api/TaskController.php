<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ShowTasksRequest;
use App\Http\Requests\DeleteTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function index(ShowTasksRequest $request): JsonResponse
    {
        $result = $this->validateToken($request->post('token'));
        if (is_bool($result)) {
            return response()->json(['message', 'User not found'], 404);
        }

        return response()->json($result->index(), 200);
    }

    public function create(StoreTaskRequest $request): JsonResponse
    {
        $result = $this->validateToken($request->post('token'));
        if (is_bool($result)) {
            return response()->json(['message', 'User not found'], 404);
        }

        return response()->json($result->create($request), 200);
    }

    public function update(UpdateTaskRequest $request): JsonResponse
    {
        $result = $this->validateToken($request->post('token'));
        if (is_bool($result)) {
            return response()->json(['message', 'User not found'], 404);
        }

        return response()->json($result->update($request), 200);
    }

    public function destroy(DeleteTaskRequest $request): JsonResponse
    {
        $result = $this->validateToken($request->post('token'));
        if (is_bool($result)) {
            return response()->json(['message', 'User not found'], 404);
        }

        return response()->json($result->destroy($request), 200);
    }

    private function validateToken($token): bool|TaskService
    {
        $user = User::query()
            ->where('api_token', $token)
            ->first();

        if ($user === null) {
            return false;
        }

        return new TaskService($user->id);
    }
}
