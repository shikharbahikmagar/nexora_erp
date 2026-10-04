<?php

namespace App\Actions\EmployeeContract;

use App\DTO\EmployeeContract\UpdateEmployeeContractDTO;
use App\Models\EmployeeContract;

class UpdateEmployeeContract
{
    /**
     * Update an existing employee contract.
     */
    public function execute(EmployeeContract $employeeContract, UpdateEmployeeContractDTO $dto): EmployeeContract
    {
        $employeeContract->update($dto->toArray());

        return $employeeContract->refresh();
    }
}
