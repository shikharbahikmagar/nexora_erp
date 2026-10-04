<?php

namespace App\Models;

use App\Enums\AttendanceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'branch_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'late_minutes',
        'early_leave_minutes',
        'worked_minutes',
        'overtime_minutes',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => AttendanceStatus::class,
            'date' => 'date',
            'check_in' => 'datetime',
            'check_out' => 'datetime',
            'late_minutes' => 'integer',
            'early_leave_minutes' => 'integer',
            'worked_minutes' => 'integer',
            'overtime_minutes' => 'integer',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
