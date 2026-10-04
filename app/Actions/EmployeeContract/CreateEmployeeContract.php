<?php

namespace App\Actions\EmployeeContract;

use App\DTO\EmployeeContract\CreateEmployeeContractDTO;
use App\Models\EmployeeContract;

class CreateEmployeeContract
{
    /**
     * Create a new employee contract.
     */
    public function execute(CreateEmployeeContractDTO $dto): EmployeeContract
    {
        return EmployeeContract::create($dto->toArray());
    }
}
