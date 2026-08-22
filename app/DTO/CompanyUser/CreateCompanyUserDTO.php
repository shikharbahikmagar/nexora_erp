<?php

namespace App\DTO\CompanyUser;

class CreateCompanyUserDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly ?int $roleId = null,
        public readonly bool $isActive = true,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            userId: $data['user_id'],
            roleId: $data['role_id'] ?? null,
            isActive: $data['is_active'] ?? true,
        );
    }
}
