<?php

namespace App\Http\Requests\Attendance;

use App\Enums\AttendanceStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateAttendanceRequest extends FormRequest
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
            'branch_id' => ['required', 'integer', 'exists:branches,id'],

            'date' => ['required', 'date'],

            'check_in' => ['nullable', 'date'],
            'check_out' => ['nullable', 'date', 'after_or_equal:check_in'],

            'status' => ['required', Rule::enum(AttendanceStatus::class)],

            'late_minutes' => ['nullable', 'integer', 'min:0'],
            'early_leave_minutes' => ['nullable', 'integer', 'min:0'],
            'worked_minutes' => ['nullable', 'integer', 'min:0'],
            'overtime_minutes' => ['nullable', 'integer', 'min:0'],

            'notes' => ['nullable', 'string'],
        ];
    }
}
