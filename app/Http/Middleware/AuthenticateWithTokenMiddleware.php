<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\TaskService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthenticateWithTokenMiddleware
{
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $token = $request->input('token') ?: $request->header('Authorization');

        if (str_starts_with($token, 'Bearer ')) {
            $token = substr($token, 7);
        }

        $user = User::query()
            ->where('api_token', $token)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->attributes->set('taskService', new TaskService($user->id));

        return $next($request);
    }
}
