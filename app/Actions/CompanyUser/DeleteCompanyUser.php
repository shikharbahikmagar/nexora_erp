<?php

namespace App\Actions\CompanyUser;

use App\Models\CompanyUser;

class DeleteCompanyUser
{
    public function execute(CompanyUser $companyUser): bool
    {
        return $companyUser->delete();
    }
}
