<?php

namespace App\Actions\Department;

use App\Models\Department;
use Illuminate\Validation\ValidationException;

class DeleteDepartment
{
    public function execute(Department $department): void
    {
        if ($department->employees()->exists()) {
            throw ValidationException::withMessages([
                'department' => 'Cannot delete a department that has employees.',
            ]);
        }

        $department->delete();
    }
}
