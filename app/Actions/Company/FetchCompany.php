<?php

namespace App\Actions\Company;

use App\Models\Company;
use Illuminate\Pagination\LengthAwarePaginator;

class FetchCompany
{
    /**
     * @return LengthAwarePaginator<int, Company>
     */


    public function execute(?string $search = null, int $perPage = 10,): LengthAwarePaginator
    {
        return Company::query()
            ->with('branches', 'companyUsers.employee')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'asc')
            ->paginate($perPage);
    }
}
