<?php

namespace App\Actions\Company;

use App\Models\Company;

class GetCompany
{
    public function execute(int $companyId): Company
    {
        return Company::with('branches')->findOrFail($companyId);
    }
}
