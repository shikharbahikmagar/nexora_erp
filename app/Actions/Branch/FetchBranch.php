<?php

namespace App\Actions\Branch;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Collection;

class FetchBranch
{
    public function execute(): Collection
    {
        return Branch::with('company')->get();
    }
}
