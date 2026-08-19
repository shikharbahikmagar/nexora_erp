<?php

namespace App\Actions\Branch;

use App\DTO\Branch\UpdateBranchDTO;
use App\Models\Branch;

class UpdateBranch
{
    public function execute(Branch $branch, UpdateBranchDTO $dto): Branch
    {
        $branch->update($dto->toArray());

        return $branch->fresh();
    }
}
