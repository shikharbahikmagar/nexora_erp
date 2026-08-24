<?php

namespace App\Http\Requests\Employee;

use App\Models\Branch;
use App\Models\CompanyUser;
use App\Models\Employee;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Employee $employee */
        $employee = $this->route('employee');
        $user = $this->user();

        if ($user === null || ! $user->can('update', $employee)) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        $companyUser = CompanyUser::find($this->input('company_user_id', $employee->company_user_id));
        $branch = Branch::find($this->input('branch_id', $employee->branch_id));

        if ($companyUser === null || $branch === null) {
            return true;
        }

        return $companyUser->company_id === $branch->company_id
            && CompanyUser::query()
                ->where('company_id', $branch->company_id)
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string|Rule>>
     */
    public function rules(): array
    {
        return [
            'company_user_id' => ['sometimes', 'integer', 'exists:company_users,id'],
            'branch_id' => ['sometimes', 'integer', 'exists:branches,id'],
            'employee_code' => ['sometimes', 'string', 'max:255', Rule::unique('employees', 'employee_code')->ignore($this->route('employee'))],
            'first_name' => ['sometimes', 'string', 'max:255'],
            'middle_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'date_of_birth' => ['sometimes', 'nullable', 'date'],
            'gender' => ['sometimes', 'nullable', 'string', 'max:255'],
            'marital_status' => ['sometimes', 'nullable', 'string', 'max:255'],
            'nationality' => ['sometimes', 'nullable', 'string', 'max:255'],
            'profile_photo' => ['sometimes', 'nullable', 'string', 'max:255'],
            'personal_email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:255'],
            'alternate_phone' => ['sometimes', 'nullable', 'string', 'max:255'],
            'joining_date' => ['sometimes', 'date'],
            'employment_type' => ['sometimes', 'string', 'max:255'],
            'employment_status' => ['sometimes', 'string', 'max:255'],
            'probation_end_date' => ['sometimes', 'nullable', 'date'],
            'confirmation_date' => ['sometimes', 'nullable', 'date'],
            'resignation_date' => ['sometimes', 'nullable', 'date'],
            'termination_date' => ['sometimes', 'nullable', 'date'],
            'termination_reason' => ['sometimes', 'nullable', 'string'],
            'notes' => ['sometimes', 'nullable', 'string'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            /** @var Employee $employee */
            $employee = $this->route('employee');
            $companyUser = CompanyUser::find($this->input('company_user_id', $employee->company_user_id));
            $branch = Branch::find($this->input('branch_id', $employee->branch_id));

            if ($companyUser !== null && $branch !== null && $companyUser->company_id !== $branch->company_id) {
                $validator->errors()->add('branch_id', 'The branch must belong to the company user\'s company.');
            }
        }];
    }
}
