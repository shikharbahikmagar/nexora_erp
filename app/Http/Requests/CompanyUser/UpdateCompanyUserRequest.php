<?php

namespace App\Http\Requests\CompanyUser;

use App\Models\CompanyUser;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyUserRequest extends FormRequest
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
        /** @var CompanyUser $companyUser */
        $companyUser = $this->route('companyUser');

        return [
            'user_id' => [
                'sometimes',
                'integer',
                'exists:users,id',
                Rule::unique('company_users', 'user_id')
                    ->where('company_id', $companyUser->company_id)
                    ->ignore($companyUser),
            ],
            'role_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:roles,id',
            ],
            'is_active' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}
