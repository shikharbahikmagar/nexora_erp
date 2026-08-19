<?php

namespace App\Actions\Branch;

use App\Models\Branch;

class DeleteBranch
{
    public function execute(Branch $branch): bool
    {
        return $branch->delete();
    }
}
