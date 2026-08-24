<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;

class EmployeePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('employees.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Employee $employee): bool
    {
        return $user->can('employees.view') && $this->belongsToEmployeeCompany($user, $employee);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('employees.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Employee $employee): bool
    {
        return $user->can('employees.update') && $this->belongsToEmployeeCompany($user, $employee);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Employee $employee): bool
    {
        return $user->can('employees.delete') && $this->belongsToEmployeeCompany($user, $employee);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Employee $employee): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Employee $employee): bool
    {
        return false;
    }

    private function belongsToEmployeeCompany(User $user, Employee $employee): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        $companyId = $employee->branch()->value('company_id');

        return $companyId !== null && $user->companyUsers()
            ->where('company_id', $companyId)
            ->where('is_active', true)
            ->exists();
    }
}
