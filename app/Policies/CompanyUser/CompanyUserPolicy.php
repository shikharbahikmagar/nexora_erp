<?php

namespace App\Policies\CompanyUser;

use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CompanyUserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CompanyUser $companyUser): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CompanyUser $companyUser): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CompanyUser $companyUser): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CompanyUser $companyUser): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CompanyUser $companyUser): bool
    {
        return false;
    }
}


/* <?php */
/**/
/* namespace App\Policies\CompanyUser; */
/**/
/* use App\Models\Company; */
/* use App\Models\CompanyUser; */
/* use App\Models\User; */
/**/
/* class CompanyUserPolicy */
/* { */
/*     public function viewAny(User $user, Company $company): bool */
/*     { */
/*         return $user->can('company_users.view') */
/*             && $this->belongsToCompany($user, $company); */
/*     } */
/**/
/*     public function view( */
/*         User $user, */
/*         Company $company, */
/*         CompanyUser $companyUser */
/*     ): bool { */
/*         return $user->can('company_users.view') */
/*             && $companyUser->company_id === $company->id */
/*             && $this->belongsToCompany($user, $company); */
/*     } */
/**/
/*     public function create(User $user, Company $company): bool */
/*     { */
/*         return $user->can('company_users.create') */
/*             && $this->belongsToCompany($user, $company); */
/*     } */
/**/
/*     public function update( */
/*         User $user, */
/*         Company $company, */
/*         CompanyUser $companyUser */
/*     ): bool { */
/*         return $user->can('company_users.update') */
/*             && $companyUser->company_id === $company->id */
/*             && $this->belongsToCompany($user, $company); */
/*     } */
/**/
/*     public function delete( */
/*         User $user, */
/*         Company $company, */
/*         CompanyUser $companyUser */
/*     ): bool { */
/*         return $user->can('company_users.delete') */
/*             && $companyUser->company_id === $company->id */
/*             && $this->belongsToCompany($user, $company); */
/*     } */
/**/
/*     private function belongsToCompany(User $user, Company $company): bool */
/*     { */
/*         return CompanyUser::query() */
/*             ->where('company_id', $company->id) */
/*             ->where('user_id', $user->id) */
/*             ->where('is_active', true) */
/*             ->exists(); */
/*     } */
/* } */
