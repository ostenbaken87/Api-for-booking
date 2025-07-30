<?php

namespace App\Actions\Auth;

use App\Dto\Auth\RegisterUserDto;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsAction;

class RegisterUserAction
{
    use AsAction;

    public function handle(RegisterUserDto $dto): array
    {
        try {
            $user = $this->createUser($dto);
            $token = $this->createAuthToken($user);

            return [
                'user' => $user,
                'token' => $token
            ];
        } catch (\Exception $e) {
            Log::error('Register failed: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function createUser(RegisterUserDto $dto): User
    {
        return User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
        ]);
    }

    protected function createAuthToken(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }
}
