<?php

namespace App\Actions\CompanyUser;

use App\DTO\CompanyUser\UpdateCompanyUserDTO;
use App\Models\CompanyUser;

class UpdateCompanyUser
{
    public function execute(CompanyUser $companyUser, UpdateCompanyUserDTO $dto): CompanyUser
    {
        $companyUser->update($dto->toArray());

        return $companyUser->refresh()->load(['company',  'user']);
    }
}
