<?php

namespace App\Http\Requests\EmployeeContract;

use App\Enums\EmployeeContractStatus;
use App\Enums\EmployeeContractType;
use App\Enums\SalaryType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateEmployeeContractRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'contract_number' => ['required', 'string', 'max:100', 'unique:employee_contracts,contract_number'],
            'contract_type' => ['required', Rule::enum(EmployeeContractType::class)],

            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],

            'probation_start_date' => ['nullable', 'date'],
            'probation_end_date' => ['nullable', 'date', 'after_or_equal:probation_start_date'],

            'designation_id' => ['nullable', 'integer', 'exists:designations,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'branch_id' => ['nullable', 'integer', 'exists:branches,id'],

            'employment_status' => ['required', Rule::enum(EmployeeContractStatus::class)],

            'salary' => ['required', 'numeric', 'min:0'],
            'salary_type' => ['required', Rule::enum(SalaryType::class)],

            'working_hours_per_week' => ['nullable', 'numeric', 'min:0', 'max:168'],
            'work_schedule' => ['nullable', 'string', 'max:255'],

            'notice_period_days' => ['nullable', 'integer', 'min:0'],

            'termination_date' => ['nullable', 'date'],
            'termination_reason' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
