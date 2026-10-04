<?php

namespace App\DTO\EmployeeContract;

use App\Enums\EmployeeContractStatus;
use App\Enums\EmployeeContractType;
use App\Enums\SalaryType;

final readonly class CreateEmployeeContractDTO
{
    public function __construct(
        public int $employeeId,
        public string $contractNumber,
        public EmployeeContractType $contractType,
        public string $startDate,
        public ?string $endDate,
        public ?string $probationStartDate,
        public ?string $probationEndDate,
        public ?int $designationId,
        public ?int $departmentId,
        public ?int $branchId,
        public EmployeeContractStatus $employmentStatus,
        public float $salary,
        public SalaryType $salaryType,
        public ?float $workingHoursPerWeek,
        public ?string $workSchedule,
        public ?int $noticePeriodDays,
        public ?string $terminationDate,
        public ?string $terminationReason,
        public ?string $notes,
        public ?int $createdBy,
    ) {}

    public static function fromArray(array $data, ?int $createdBy = null): self
    {
        return new self(
            employeeId: $data['employee_id'],
            contractNumber: $data['contract_number'],
            contractType: EmployeeContractType::from($data['contract_type']),
            startDate: $data['start_date'],
            endDate: $data['end_date'] ?? null,
            probationStartDate: $data['probation_start_date'] ?? null,
            probationEndDate: $data['probation_end_date'] ?? null,
            designationId: $data['designation_id'] ?? null,
            departmentId: $data['department_id'] ?? null,
            branchId: $data['branch_id'] ?? null,
            employmentStatus: EmployeeContractStatus::from($data['employment_status']),
            salary: (float) $data['salary'],
            salaryType: SalaryType::from($data['salary_type']),
            workingHoursPerWeek: isset($data['working_hours_per_week']) ? (float) $data['working_hours_per_week'] : null,
            workSchedule: $data['work_schedule'] ?? null,
            noticePeriodDays: $data['notice_period_days'] ?? null,
            terminationDate: $data['termination_date'] ?? null,
            terminationReason: $data['termination_reason'] ?? null,
            notes: $data['notes'] ?? null,
            createdBy: $createdBy,
        );
    }

    public function toArray(): array
    {
        return [
            'employee_id' => $this->employeeId,
            'contract_number' => $this->contractNumber,
            'contract_type' => $this->contractType->value,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'probation_start_date' => $this->probationStartDate,
            'probation_end_date' => $this->probationEndDate,
            'designation_id' => $this->designationId,
            'department_id' => $this->departmentId,
            'branch_id' => $this->branchId,
            'employment_status' => $this->employmentStatus->value,
            'salary' => $this->salary,
            'salary_type' => $this->salaryType->value,
            'working_hours_per_week' => $this->workingHoursPerWeek,
            'work_schedule' => $this->workSchedule,
            'notice_period_days' => $this->noticePeriodDays,
            'termination_date' => $this->terminationDate,
            'termination_reason' => $this->terminationReason,
            'notes' => $this->notes,
            'created_by' => $this->createdBy,
        ];
    }
}
