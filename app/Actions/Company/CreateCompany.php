<?php

namespace App\Actions\Company;

use App\DTO\Company\CreateCompanyDTO;
use App\Models\Company;

class CreateCompany
{
    public function execute(CreateCompanyDTO $dto): Company
    {
        return Company::create($dto->toArray());
    }
}
