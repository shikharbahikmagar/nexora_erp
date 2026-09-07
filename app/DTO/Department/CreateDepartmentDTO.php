<?php

namespace App\DTO\Department;

class CreateDepartmentDTO
{
    public function __construct(
        public int $companyId,
        public string $name,
        public ?string $code = null,
        public ?string $description = null,
        public bool $isActive = true,
    ) {}
}
