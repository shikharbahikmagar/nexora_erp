<?php

namespace App\Actions\Employee;

use App\Models\CompanyUser;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class FetchEmployee
{
    /**
     * @return LengthAwarePaginator<int, Employee>
     */
    public function execute(User $user, ?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Employee::query()
            ->with(['companyUser.company', 'companyUser.user', 'branch'])
            ->when(! $user->hasRole('super_admin'), function ($query) use ($user) {
                $query->whereHas('branch', function ($query) use ($user) {
                    $query->whereIn('company_id', CompanyUser::query()
                        ->where('user_id', $user->id)
                        ->where('is_active', true)
                        ->select('company_id'));
                });
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('employee_code', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('personal_email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($perPage);
    }
}
