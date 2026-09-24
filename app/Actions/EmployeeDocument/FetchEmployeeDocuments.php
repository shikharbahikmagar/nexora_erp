<?php

namespace App\Actions\EmployeeDocument;

use App\Models\Employee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FetchEmployeeDocuments
{
    public function execute(Employee $employee): LengthAwarePaginator
    {
        return $employee->documents()
            ->latest()
            ->paginate();
    }
}
