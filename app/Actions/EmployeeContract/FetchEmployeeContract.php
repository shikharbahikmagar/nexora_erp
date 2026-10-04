<?php

namespace App\Actions\EmployeeContract;

use App\Models\EmployeeContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FetchEmployeeContract
{
    /**
     * Fetch paginated employee contracts.
     */
    public function execute(
        ?string $search = null,
        ?int $employeeId = null,
        ?string $status = null,
        ?string $contractType = null,
        int $perPage = 15,
    ): LengthAwarePaginator {
        return EmployeeContract::query()
            ->with([
                'employee',
                'designation',
                'department',
                'branch',
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('contract_number', 'ilike', "%{$search}%")
                        ->orWhereHas('employee', function ($query) use ($search) {
                            $query->where('employee_code', 'ilike', "%{$search}%")
                                ->orWhere('first_name', 'ilike', "%{$search}%")
                                ->orWhere('last_name', 'ilike', "%{$search}%");
                        });
                });
            })
            ->when($employeeId, function ($query) use ($employeeId) {
                $query->where('employee_id', $employeeId);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('employment_status', $status);
            })
            ->when($contractType, function ($query) use ($contractType) {
                $query->where('contract_type', $contractType);
            })
            ->orderBy('id', 'asc')
            ->paginate($perPage);
    }
}
