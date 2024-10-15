<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetTokenRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TokenController extends Controller
{
    public function getToken(GetTokenRequest $request): JsonResponse
    {
        $user = User::query()
            ->where([
                'email' => $request->post('email'),
            ])
            ->first();

        if ($user === null) {
            return response()->json(['message' => 'user not found'], 404);
        }

        if (!Hash::check($request->post('password'), $user->password)) {
            return response()->json(['message' => 'user not found'], 404);
        }

        return response()->json(['token' => $user->api_token], 200);
    }
}
