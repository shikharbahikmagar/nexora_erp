<?php

namespace App\Actions\Employee;

use App\Actions\CompanyUser\CreateCompanyUser;
use App\DTO\CompanyUser\CreateCompanyUserDTO;
use App\DTO\Employee\CreateEmployeeDTO;
use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;

class CreateEmployee
{
    // Removed self-injection
    public function __construct(
        protected CreateCompanyUser $createCompanyUser
    ) {}

    public function execute(Company $company, CreateEmployeeDTO $dto): Employee
    {
        return DB::transaction(function () use ($company, $dto) {
            try {
                // 1. Create Base User Account
                $user = User::create([
                    'email'    => $dto->email,
                    'name' => $dto->first_name,
                    'password' => Hash::make($dto->password),
                ]);

                // 2. Attach User to Tenant Company
                $companyUser = $this->createCompanyUser->execute(
                    $company,
                    CreateCompanyUserDTO::fromArray([
                        'user_id' => $user->id,
                    ])
                );

                // 3. Create HR Employee Profile using DTO mapping
                return Employee::create($dto->toArray($companyUser->id));
            } catch (Throwable $e) {
                Log::error('Employee registration failed: ' . $e->getMessage(), [
                    'email'      => $dto->email,
                    'company_id' => $company->id,
                    'trace'      => $e->getTraceAsString(),
                ]);

                throw $e;
            }
        });
    }
}
