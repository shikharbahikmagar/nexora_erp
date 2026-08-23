<?php

namespace App\Actions\CompanyUser;

use App\Models\CompanyUser;
use Illuminate\Pagination\LengthAwarePaginator;

class FetchCompanyUser
{
    /**
     * @return LengthAwarePaginator<int, CompanyUser>
     */
    public function execute(int $perPage = 10): LengthAwarePaginator
    {
        return CompanyUser::query()
            ->with(['company', 'role', 'user'])
            ->latest()
            ->paginate($perPage);
    }
}
