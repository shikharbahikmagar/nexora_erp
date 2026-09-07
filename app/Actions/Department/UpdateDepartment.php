<?php

namespace App\Actions\Department;

use App\DTO\Department\UpdateDepartmentDTO;
use App\Models\Department;

class UpdateDepartment
{
    public function execute(Department $department, UpdateDepartmentDTO $dto): Department
    {
        $department->update([
            'name' => $dto->name,
            'code' => $dto->code,
            'description' => $dto->description,
            'is_active' => $dto->isActive,
        ]);

        return $department->fresh();
    }
}
