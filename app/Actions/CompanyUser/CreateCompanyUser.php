<?php

namespace App\Actions\CompanyUser;

use App\DTO\CompanyUser\CreateCompanyUserDTO;
use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Validation\ValidationException;

class CreateCompanyUser
{
    public function execute(Company $company, CreateCompanyUserDTO $dto): CompanyUser
    {
        $exists = CompanyUser::query()
            ->where('company_id', $company->id)
            ->where('user_id', $dto->userId)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'user_id' => 'User already belongs to this company.',
            ]);
        }

        return CompanyUser::create([
            'company_id' => $company->id,
            'user_id' => $dto->userId,
            'role_id' => $dto->roleId,
            'is_active' => $dto->isActive,
        ]);
    }
}
