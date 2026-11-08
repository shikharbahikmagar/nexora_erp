<?php

namespace App\Actions\CompanyUser;

use App\DTO\CompanyUser\CreateCompanyUserDTO;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CreateCompanyUser
{
    public function execute(Company $company, CreateCompanyUserDTO $dto): CompanyUser
    {
        return DB::transaction(function () use ($company, $dto): CompanyUser {
            $user = User::query()->where('email', $dto->email)->first();

            if ($user === null) {
                $user = User::create([
                    'name' => $dto->name,
                    'email' => $dto->email,
                    'password' => Hash::make($dto->password),
                ]);
            }

            $membershipExists = CompanyUser::query()
                ->where('company_id', $company->id)
                ->where('user_id', $user->id)
                ->exists();

            if ($membershipExists) {
                throw ValidationException::withMessages([
                    'email' => 'User already belongs to this company.',
                ]);
            }

            return CompanyUser::create([
                'company_id' => $company->id,
                'user_id' => $user->id,
                'is_active' => $dto->isActive,
            ])->load(['company', 'user']);
        });
    }
}
