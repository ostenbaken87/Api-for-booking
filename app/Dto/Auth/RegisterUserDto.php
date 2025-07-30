<?php

namespace App\Dto\Auth;

use App\Http\Requests\Auth\AuthRegisterRequest;

class RegisterUserDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $created_at = null,
        public readonly ?string $updated_at = null
    ){}

    public static function fromRequest(AuthRegisterRequest $request): self
    {
        return new self(
            name: $request->name,
            email: $request->email,
            password: $request->password,
            created_at: $request->created_at,
            updated_at: $request->updated_at
        );
    }
}
