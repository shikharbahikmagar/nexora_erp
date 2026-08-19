<?php

namespace App\Http\Requests\Branch;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBranchRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:255'],

            'code' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('branches', 'code')->ignore($this->route('branch')),
            ],

            'email' => ['sometimes', 'nullable', 'email'],
            'phone' => ['sometimes', 'nullable', 'string'],
            'address' => ['sometimes', 'nullable', 'string'],
            'city' => ['sometimes', 'nullable', 'string'],
            'state' => ['sometimes', 'nullable', 'string'],
            'country' => ['sometimes', 'nullable', 'string'],
            'postal_code' => ['sometimes', 'nullable', 'string'],

            'latitude' => ['sometimes', 'nullable', 'numeric'],
            'longitude' => ['sometimes', 'nullable', 'numeric'],

            'manager_name' => ['sometimes', 'nullable', 'string'],
            'timezone' => ['sometimes', 'nullable', 'string'],
            'is_head_office' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'description' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
