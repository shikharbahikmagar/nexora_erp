<?php

namespace App\Actions\Branch;

use App\Models\Branch;

class GetBranch
{
    public function execute(Branch $branch): Branch
    {
        return $branch->load('Company');
    }
}
