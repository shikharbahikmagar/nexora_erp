<?php

namespace App\Actions\Branch;

use App\Models\Branch;

class GetBranch
{
    public function execute(int $branchId): Branch
    {
        return Branch::with('Company')->findOrFail($branchId);
    }
}
