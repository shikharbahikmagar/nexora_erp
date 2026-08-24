<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_user_id',
        'branch_id',
        'employee_code',
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'gender',
        'marital_status',
        'nationality',
        'profile_photo',
        'personal_email',
        'phone',
        'alternate_phone',
        'joining_date',
        'employment_type',
        'employment_status',
        'probation_end_date',
        'confirmation_date',
        'resignation_date',
        'termination_date',
        'termination_reason',
        'notes',
    ];

    public function companyUser(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'joining_date' => 'date',
            'probation_end_date' => 'date',
            'confirmation_date' => 'date',
            'resignation_date' => 'date',
            'termination_date' => 'date',
        ];
    }
}
