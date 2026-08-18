<?php

namespace App\Actions\Company;

use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;

class FetchCompany
{
    /**
     * @return Collection<int, Company>
     */
    public function execute(): Collection
    {
        return Company::with('branches')->get();
    }
}
