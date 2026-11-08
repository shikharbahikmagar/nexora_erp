<?php

namespace App\Actions\Company;

use App\Models\Company;

class GetCompany
{
    public function execute(Company $company): Company
    {
        return $company->load('branches');
    }
}
