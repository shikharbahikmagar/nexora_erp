<?php

namespace App\DTO\Employee;

use App\Enums\EmploymentStatus;
use App\Enums\EmploymentType;
use App\Http\Requests\Employee\CreateEmployeeRequest;
use Carbon\Carbon;

readonly class CreateEmployeeDTO
{
    public function __construct(
        // Auth & Identity
        public int $company_id,
        public string $email,
        public string $password,

        // Employee Identification
        public string $employee_code,
        public string $first_name,
        public string $last_name,
        public ?string $middle_name = null,

        // Organizational Structure
        public ?int $branch_id = null,

        // Employment Configuration
        public ?Carbon $joining_date = null,
        public EmploymentType $employment_type = EmploymentType::FULL_TIME,
        public EmploymentStatus $employment_status = EmploymentStatus::ACTIVE,

        // Contact Details
        public ?string $personal_email = null,
        public ?string $phone = null,

        // Optional Meta
        public ?string $notes = null,
    ) {}

    /**
     * Factory constructor from standard Form Request
     */
    public static function fromRequest(CreateEmployeeRequest $request, int $companyId): self
    {
        return new self(
            company_id: $companyId,
            email: $request->validated('email'),
            password: $request->validated('password'),
            employee_code: $request->validated('employee_code'),
            first_name: $request->validated('first_name'),
            last_name: $request->validated('last_name'),
            middle_name: $request->validated('middle_name'),
            branch_id: $request->validated('branch_id') ? (int) $request->validated('branch_id') : null,
            joining_date: $request->validated('joining_date')
                ? Carbon::parse($request->validated('joining_date'))
                : now(),
            employment_type: EmploymentType::tryFrom($request->validated('employment_type') ?? '')
                ?? EmploymentType::FULL_TIME,
            employment_status: EmploymentStatus::tryFrom($request->validated('employment_status') ?? '')
                ?? EmploymentStatus::ACTIVE,
            personal_email: $request->validated('personal_email'),
            phone: $request->validated('phone'),
            notes: $request->validated('notes'),
        );
    }

    /**
     * Factory constructor for array payloads (jobs, seeds, tests)
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            company_id: (int) $data['company_id'],
            email: (string) $data['email'],
            password: (string) $data['password'],
            employee_code: (string) $data['employee_code'],
            first_name: (string) $data['first_name'],
            last_name: (string) $data['last_name'],
            middle_name: $data['middle_name'] ?? null,
            branch_id: isset($data['branch_id']) ? (int) $data['branch_id'] : null,
            joining_date: isset($data['joining_date']) ? Carbon::parse($data['joining_date']) : now(),
            employment_type: is_string($data['employment_type'] ?? null)
                ? (EmploymentType::tryFrom($data['employment_type']) ?? EmploymentType::FULL_TIME)
                : ($data['employment_type'] ?? EmploymentType::FULL_TIME),
            employment_status: is_string($data['employment_status'] ?? null)
                ? (EmploymentStatus::tryFrom($data['employment_status']) ?? EmploymentStatus::ACTIVE)
                : ($data['employment_status'] ?? EmploymentStatus::ACTIVE),
            personal_email: $data['personal_email'] ?? null,
            phone: $data['phone'] ?? null,
            notes: $data['notes'] ?? null,
        );
    }

    /**
     * Converts DTO into Eloquent creation payload for the Employee model
     *
     * @return array<string, mixed>
     */
    public function toArray(int $companyUserId): array
    {
        return array_filter([
            'company_user_id'   => $companyUserId,
            'branch_id'         => $this->branch_id,
            'employee_code'     => $this->employee_code,
            'first_name'        => $this->first_name,
            'middle_name'       => $this->middle_name,
            'last_name'         => $this->last_name,
            'personal_email'    => $this->personal_email,
            'phone'             => $this->phone,
            'joining_date'      => $this->joining_date?->toDateString(),
            'employment_type'   => $this->employment_type->value,
            'employment_status' => $this->employment_status->value,
            'notes'             => $this->notes,
        ], fn($value) => $value !== null);
    }
}
