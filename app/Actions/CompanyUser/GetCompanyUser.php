<?php

namespace App\Actions\CompanyUser;

use App\Models\CompanyUser;

class GetCompanyUser
{
    public function execute(CompanyUser $companyUser): CompanyUser
    {
        return $companyUser->load(['company', 'role', 'user']);
    }
}
