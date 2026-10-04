<?php

namespace App\Actions\Attendance;

use App\Models\Attendance;

class GetAttendance
{
    /**
     * Get a single attendance record with its relations.
     */
    public function execute(Attendance $attendance): Attendance
    {
        return $attendance->load(['employee', 'branch']);
    }
}
