<?php

namespace App\Actions\Company;

use App\Models\Company;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class FetchCompany
{
    /**
     * @return LengthAwarePaginator<int, Company>
     */

    public function __construct()
    {

        Log::info('check this', [
            'this' => $this,
        ]);
    }


    public function execute(?string $search = null, int $perPage = 10,): LengthAwarePaginator
    {
        return Company::query()
            ->with('branches')
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
