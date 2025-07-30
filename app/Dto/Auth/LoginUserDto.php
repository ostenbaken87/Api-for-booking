<?php

namespace App\Dto\Auth;

use App\Http\Requests\Auth\AuthLoginRequest;

class LoginUserDto
{
    public function __construct(
        public readonly string $email,
        public readonly string $password
    ) {}

    public static function fromRequest(AuthLoginRequest $request): self
    {
        return new self(
            email: $request->email,
            password: $request->password
        );
    }
}
