<?php

namespace App\Actions\Branch;

use App\DTO\Branch\CreateBranchDTO;
use App\Models\Branch;

class CreateBranch
{
    public function execute(CreateBranchDTO $dto): Branch
    {
        return Branch::create($dto->toArray());
    }
}
