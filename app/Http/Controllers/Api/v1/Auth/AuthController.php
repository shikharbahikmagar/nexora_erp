<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\RegisterAction;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     *Register New User
     */

    public function register(RegisterRequest $request, RegisterAction $action): JsonResponse
    {
        $result = $action->execute(
            $request->string('name')->toString(),
            $request->string('email')->toString(),
            $request->string('password')->toString(),
        );

        return response()->json([
            'message' => 'Registration successful.',
            'data' => $result,
        ], 201);
    }

    /**
     *Login User
     */

    public function login(LoginRequest $request, LoginAction $action): JsonResponse
    {
        $result = $action->execute(
            $request->string('email')->toString(),
            $request->string('password')->toString(),
        );

        return ApiResponse::success(
            $result,
            'Login successful.',
        );
    }

    /**
     *Get Me
     */

    public function me(): JsonResponse
    {
        $user = Auth::user();

        return ApiResponse::success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->getRoleNames()->first(),
        ], 'Profile Fetched Successfully.');
    }
    /**
     *Logout Me
     */

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::success(
            message: 'Logged out successfully.'
        );
    }
}
