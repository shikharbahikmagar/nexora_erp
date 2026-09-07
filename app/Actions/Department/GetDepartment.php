<?php

namespace App\Actions\Department;

use App\Models\Department;

class GetDepartment
{
    public function execute(Department $department): Department
    {
        return $department->load('company');
    }
}
