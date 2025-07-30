<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Dto\Auth\LoginUserDto;
use App\Dto\Auth\RegisterUserDto;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Actions\Auth\LoginUserAction;
use App\Actions\Auth\RegisterUserAction;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Exceptions\Auth\InvalidCredentialsException;

class AuthController extends BaseController
{
    public function __construct(
        private RegisterUserAction $registerAction,
        private LoginUserAction $loginAction
    ) {}

    public function register(AuthRegisterRequest $request): JsonResponse
    {
        try {
            $dto = RegisterUserDto::fromRequest($request);
            $result = $this->registerAction->handle($dto);

            return $this->successResponse(
                $result,
                'User registered successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Register failed',
                500
            );
        }
    }

    public function login(AuthLoginRequest $request): JsonResponse
    {
        try {
            $dto = LoginUserDto::fromRequest($request);
            $result = $this->loginAction->handle($dto);

            return $this->successResponse(
                $result,
                'User logged in successfully',
                200
            );
        } catch (InvalidCredentialsException $e) {
            return $this->errorResponse(
                'Invalid credentials',
                401
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: 'Login failed',
                code: 500
            );
        }
    }

    public function logout(): JsonResponse
    {
        /**
         * @var User $user
        */

        $user = Auth::user();

        $user->tokens()->delete();

        return $this->successResponse(
            data: null,
            message: 'User logged out successfully',
            code: 200
        );
    }
}
