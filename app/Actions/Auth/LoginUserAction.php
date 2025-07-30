<?php

namespace App\Actions\Auth;

use App\Dto\Auth\LoginUserDto;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Exceptions\Auth\InvalidCredentialsException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginUserAction
{
    use AsAction;

    public function handle(LoginUserDto $dto): array
    {
        try {
            if (!$this->attemptLogin($dto)) {
                throw new InvalidCredentialsException();
            }

            $user = $this->getAuthenticatedUser();
            $token = $this->createAuthToken($user);

            $this->clearOldToken($user);

            return [
                'user' => $user,
                'token' => $token
            ];
        } catch (\Exception $e) {
            Log::error('Login failed: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function attemptLogin(LoginUserDto $dto): bool
    {
        return Auth::attempt([
            'email' => $dto->email,
            'password' => $dto->password
        ]);
    }

    protected function getAuthenticatedUser(): object
    {
        return Auth::user();
    }

    protected function createAuthToken(object $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }

    protected function clearOldToken ($user, int $keepLast = 3): void
    {
        $tokens = $user->tokens()->orderBy('created_at', 'desc')->get();

        if ($tokens->count() > $keepLast) {
            $tokens->slice($keepLast)->each->delete();
        }
    }
}
