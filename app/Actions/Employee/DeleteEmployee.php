<?php

namespace App\Actions\Employee;

use App\Models\Employee;

class DeleteEmployee
{
    public function execute(Employee $employee): bool
    {
        return $employee->delete();
    }
}
