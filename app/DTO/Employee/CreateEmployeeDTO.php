<?php

namespace App\DTO\Employee;

use App\Enums\EmploymentStatus;
use App\Enums\EmploymentType;
use App\Http\Requests\Employee\CreateEmployeeRequest;
use Carbon\Carbon;

readonly class CreateEmployeeDTO
{
    public function __construct(
        public int $companyUserId,
        public string $employeeCode,
        public string $firstName,
        public string $lastName,
        public ?string $middleName = null,
        public ?int $branchId = null,
        public ?Carbon $joiningDate = null,
        public EmploymentType $employmentType = EmploymentType::FULL_TIME,
        public EmploymentStatus $employmentStatus = EmploymentStatus::ACTIVE,
        public ?string $dateOfBirth = null,
        public ?string $gender = null,
        public ?string $maritalStatus = null,
        public ?string $nationality = null,
        public ?string $profilePhoto = null,
        public ?string $personalEmail = null,
        public ?string $phone = null,
        public ?string $alternatePhone = null,
        public ?string $probationEndDate = null,
        public ?string $confirmationDate = null,
        public ?string $resignationDate = null,
        public ?string $terminationDate = null,
        public ?string $terminationReason = null,
        public ?string $notes = null,
    ) {}

    public static function fromRequest(CreateEmployeeRequest $request): self
    {
        return self::fromArray($request->validated());
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            companyUserId: (int) $data['company_user_id'],
            employeeCode: (string) $data['employee_code'],
            firstName: (string) $data['first_name'],
            lastName: (string) $data['last_name'],
            middleName: $data['middle_name'] ?? null,
            branchId: isset($data['branch_id']) ? (int) $data['branch_id'] : null,
            joiningDate: isset($data['joining_date']) ? Carbon::parse($data['joining_date']) : now(),
            employmentType: is_string($data['employment_type'] ?? null)
                ? (EmploymentType::tryFrom($data['employment_type']) ?? EmploymentType::FULL_TIME)
                : ($data['employment_type'] ?? EmploymentType::FULL_TIME),
            employmentStatus: is_string($data['employment_status'] ?? null)
                ? (EmploymentStatus::tryFrom($data['employment_status']) ?? EmploymentStatus::ACTIVE)
                : ($data['employment_status'] ?? EmploymentStatus::ACTIVE),
            dateOfBirth: $data['date_of_birth'] ?? null,
            gender: $data['gender'] ?? null,
            maritalStatus: $data['marital_status'] ?? null,
            nationality: $data['nationality'] ?? null,
            profilePhoto: $data['profile_photo'] ?? null,
            personalEmail: $data['personal_email'] ?? null,
            phone: $data['phone'] ?? null,
            alternatePhone: $data['alternate_phone'] ?? null,
            probationEndDate: $data['probation_end_date'] ?? null,
            confirmationDate: $data['confirmation_date'] ?? null,
            resignationDate: $data['resignation_date'] ?? null,
            terminationDate: $data['termination_date'] ?? null,
            terminationReason: $data['termination_reason'] ?? null,
            notes: $data['notes'] ?? null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'company_user_id' => $this->companyUserId,
            'branch_id' => $this->branchId,
            'employee_code' => $this->employeeCode,
            'first_name' => $this->firstName,
            'middle_name' => $this->middleName,
            'last_name' => $this->lastName,
            'date_of_birth' => $this->dateOfBirth,
            'gender' => $this->gender,
            'marital_status' => $this->maritalStatus,
            'nationality' => $this->nationality,
            'profile_photo' => $this->profilePhoto,
            'personal_email' => $this->personalEmail,
            'phone' => $this->phone,
            'alternate_phone' => $this->alternatePhone,
            'joining_date' => $this->joiningDate?->toDateString(),
            'employment_type' => $this->employmentType->value,
            'employment_status' => $this->employmentStatus->value,
            'probation_end_date' => $this->probationEndDate,
            'confirmation_date' => $this->confirmationDate,
            'resignation_date' => $this->resignationDate,
            'termination_date' => $this->terminationDate,
            'termination_reason' => $this->terminationReason,
            'notes' => $this->notes,
        ], fn ($value) => $value !== null);
    }
}
