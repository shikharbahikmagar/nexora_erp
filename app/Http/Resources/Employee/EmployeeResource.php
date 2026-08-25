<?php

namespace App\Http\Resources\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_user' => [
                'id' => $this->company_user_id,
                'company' => $this->whenLoaded('companyUser', fn () => [
                    'id' => $this->companyUser->company_id,
                    'name' => $this->companyUser->company?->name,
                    'code' => $this->companyUser->company?->code,
                ]),
                'user' => $this->whenLoaded('companyUser', fn () => [
                    'id' => $this->companyUser->user_id,
                    'name' => $this->companyUser->user?->name,
                    'email' => $this->companyUser->user?->email,
                ]),
            ],
            'branch' => [
                'id' => $this->branch_id,
                'name' => $this->whenLoaded('branch', fn () => $this->branch->name),
                'code' => $this->whenLoaded('branch', fn () => $this->branch->code),
            ],
            'employee_code' => $this->employee_code,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'date_of_birth' => $this->date_of_birth?->toDateString(),
            'gender' => $this->gender,
            'marital_status' => $this->marital_status,
            'nationality' => $this->nationality,
            'profile_photo' => $this->profile_photo,
            'contact' => [
                'personal_email' => $this->personal_email,
                'phone' => $this->phone,
                'alternate_phone' => $this->alternate_phone,
            ],
            'joining_date' => $this->joining_date?->toDateString(),
            'employment_type' => $this->employment_type,
            'employment_status' => $this->employment_status,
            'probation_end_date' => $this->probation_end_date?->toDateString(),
            'confirmation_date' => $this->confirmation_date?->toDateString(),
            'resignation_date' => $this->resignation_date?->toDateString(),
            'termination_date' => $this->termination_date?->toDateString(),
            'termination_reason' => $this->termination_reason,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
