<?php

namespace App\Actions\Department;

use App\Models\Department;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FetchDepartment
{
    public function execute(int $companyId, ?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return Department::query()
            ->where('company_id', $companyId)
            ->when($search, function ($query) use ($search) {
                $query
                    ->where('name', 'ilike', "%{$search}%")
                    ->orWhere('code', 'ilike', "%{$search}%");
            })
            ->orderBy('id', 'asc')
            ->paginate($perPage);
    }
}
