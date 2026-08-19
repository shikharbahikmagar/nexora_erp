<?php

namespace App\Actions\Company;

use App\DTO\Company\UpdateCompanyDTO;
use App\Models\Company;
use Illuminate\Support\Facades\Log;

class UpdateCompany
{
    public function execute(Company $company, UpdateCompanyDTO $dto): Company
    {

        /* Log::info('Updating company', [ */
        /*     'company_id' => $company->id, */
        /*     'data' => $dto->toArray(), */
        /* ]); */

        $company->update($dto->toArray());

        return $company->refresh();
    }
}
