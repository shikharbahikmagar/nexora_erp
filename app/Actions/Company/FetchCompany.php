<?php

namespace App\Actions\Company;

use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;

class FetchCompany
{
    public function execute(): Collection
    {
        return Company::with('branches')->get();
    }
}
