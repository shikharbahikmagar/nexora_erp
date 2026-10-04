<?php

namespace App\Actions\Attendance;

use App\Models\Attendance;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FetchAttendance
{
    /**
     * Fetch paginated attendance records.
     */
    public function execute(
        ?int $employeeId = null,
        ?int $branchId = null,
        ?string $status = null,
        ?string $fromDate = null,
        ?string $toDate = null,
        ?string $search = null,
        int $perPage = 15,
    ): LengthAwarePaginator {
        return Attendance::query()
            ->with(['employee', 'branch'])
            ->when($employeeId, function ($query) use ($employeeId) {
                $query->where('employee_id', $employeeId);
            })
            ->when($branchId, function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($fromDate, function ($query) use ($fromDate) {
                $query->whereDate('date', '>=', $fromDate);
            })
            ->when($toDate, function ($query) use ($toDate) {
                $query->whereDate('date', '<=', $toDate);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('employee', function ($query) use ($search) {
                        $query->where('employee_code', 'ilike', "%{$search}%")
                            ->orWhere('first_name', 'ilike', "%{$search}%")
                            ->orWhere('last_name', 'ilike', "%{$search}%");
                    });
                });
            })
            ->orderBy('id', 'asc')
            ->paginate($perPage);
    }
}
