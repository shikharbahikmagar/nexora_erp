<?php

namespace App\Http\Requests\CompanyUser;

use Illuminate\Foundation\Http\FormRequest;

class CreateCompanyUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],


            'is_active' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}
