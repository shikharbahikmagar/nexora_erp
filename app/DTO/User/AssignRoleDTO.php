<?php

namespace App\DTO\User;

class AssignRoleDTO
{
    public function __construct(
        public readonly string $role,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            role: $data['role'],
        );
    }
}
