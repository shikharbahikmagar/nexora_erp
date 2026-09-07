<?php

namespace App\DTO\Department;

class UpdateDepartmentDTO
{
    public function __construct(
        public string $name,
        public ?string $code = null,
        public ?string $description = null,
        public bool $isActive = true,
    ) {}
}
