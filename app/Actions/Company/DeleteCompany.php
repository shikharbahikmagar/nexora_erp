<?php

namespace App\Actions\Company;

use App\Models\Company;

class DeleteCompany
{
    public function execute(Company $company): bool
    {
        return $company->delete();
    }
}
