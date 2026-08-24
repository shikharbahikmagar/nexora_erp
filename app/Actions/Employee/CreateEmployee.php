<?php

namespace App\Actions\Employee;

use App\DTO\Employee\CreateEmployeeDTO;
use App\Models\Employee;

class CreateEmployee
{
    public function execute(CreateEmployeeDTO $dto): Employee
    {
        return Employee::create($dto->toArray())->load(['companyUser.company', 'companyUser.user', 'branch']);
    }
}
