<?php

namespace App\Actions\Company;

use App\DTO\Company\CreateCompanyDTO;
use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Support\Facades\Auth;

class CreateCompany
{
    public function execute(CreateCompanyDTO $dto): Company
    {
        $company =  Company::create($dto->toArray());

        CompanyUser::create([
            'company_id' => $company->id,
            'user_id' => Auth::user()->id,
        ]);

        return $company;
    }
}
