<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
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
}
