<?php

namespace App\Actions\Employee;

use App\Models\Employee;

class GetEmployee
{
    public function execute(Employee $employee): Employee
    {
        return $employee->load(['companyUser.company', 'companyUser.user', 'branch']);
    }
}
