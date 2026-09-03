<?php

namespace App\Http\Requests\Employee;

use App\Models\Branch;
use App\Models\CompanyUser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CreateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if ($user === null || ! $user->can('employees.create')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        $companyUser = CompanyUser::find($this->integer('company_user_id'));
        $branch = Branch::find($this->integer('branch_id'));

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
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'company_user_id' => ['required', 'integer', 'exists:company_users,id'],
            'branch_id' => ['nullable', 'integer', 'exists:branches,id'],
            'employee_code' => ['required', 'string', 'max:255', 'unique:employees,employee_code'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:255'],
            'marital_status' => ['nullable', 'string', 'max:255'],
            'nationality' => ['nullable', 'string', 'max:255'],
            'profile_photo' => ['nullable', 'string', 'max:255'],
            'personal_email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'alternate_phone' => ['nullable', 'string', 'max:255'],
            'joining_date' => ['required', 'date'],
            'employment_type' => ['required', 'string', 'max:255'],
            'employment_status' => ['sometimes', 'string', 'max:255'],
            'probation_end_date' => ['nullable', 'date'],
            'confirmation_date' => ['nullable', 'date'],
            'resignation_date' => ['nullable', 'date'],
            'termination_date' => ['nullable', 'date'],
            'termination_reason' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            $companyUser = CompanyUser::find($this->integer('company_user_id'));
            $branch = Branch::find($this->integer('branch_id'));

            if ($companyUser !== null && $branch !== null && $companyUser->company_id !== $branch->company_id) {
                $validator->errors()->add('branch_id', 'The branch must belong to the company user\'s company.');
            }
        }];
    }
}
