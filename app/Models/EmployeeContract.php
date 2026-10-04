<?php

namespace App\Models;

use App\Enums\EmployeeContractStatus;
use App\Enums\EmployeeContractType;
use App\Enums\SalaryType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeContract extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'contract_number',
        'contract_type',
        'start_date',
        'end_date',
        'probation_start_date',
        'probation_end_date',
        'designation_id',
        'department_id',
        'branch_id',
        'employment_status',
        'salary',
        'salary_type',
        'working_hours_per_week',
        'work_schedule',
        'notice_period_days',
        'termination_date',
        'termination_reason',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'contract_type' => EmployeeContractType::class,
            'employment_status' => EmployeeContractStatus::class,
            'salary_type' => SalaryType::class,

            'start_date' => 'date',
            'end_date' => 'date',
            'probation_start_date' => 'date',
            'probation_end_date' => 'date',
            'termination_date' => 'date',

            'salary' => 'decimal:2',
            'working_hours_per_week' => 'decimal:2',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
