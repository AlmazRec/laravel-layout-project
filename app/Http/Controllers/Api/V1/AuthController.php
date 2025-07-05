<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Services\AuthServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\LoginUserRequest;
use App\Http\Requests\Api\v1\RegisterUserRequest;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ResponseTrait;

    public function __construct(
        private readonly AuthServiceInterface $authService
    )
    {
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->validated());

        return $this->success(
            message: 'User registered successfully',
            data: $user
        );
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        $token = $this->authService->login($request->validated());

        return $this->success(
            message: 'User logged in successfully',
            data: [
                'token' => $token,
            ]
        );
    }

    public function logout(): JsonResponse
    {
        Auth::logout();

        return $this->success(
            message: 'User logged out successfully'
        );
    }
}
