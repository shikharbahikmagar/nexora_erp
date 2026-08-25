<?php

namespace App\Actions\Employee;

use App\DTO\Employee\UpdateEmployeeDTO;
use App\Models\Employee;

class UpdateEmployee
{
    public function execute(Employee $employee, UpdateEmployeeDTO $dto): Employee
    {
        $employee->update($dto->toArray());

        return $employee->refresh()->load(['companyUser.company', 'companyUser.user', 'branch']);
    }
}
