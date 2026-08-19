<?php

namespace App\Actions\Branch;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class FetchBranch
{
    /**
     * @return Collection<int, Branch>
     */
    public function execute(?string $search = null, int $perPage = 10,): LengthAwarePaginator
    {
        return Branch::query()
            ->with('company')
            ->orderBy('id', 'asc')
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
