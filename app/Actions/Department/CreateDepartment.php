<?php

namespace App\Actions\Department;

use App\DTO\Department\CreateDepartmentDTO;
use App\Models\Department;

class CreateDepartment
{
    public function execute(CreateDepartmentDTO $dto): Department
    {
        return Department::create([
            'company_id' => $dto->companyId,
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
            'is_active' => $dto->isActive,
        ]);
    }
}
