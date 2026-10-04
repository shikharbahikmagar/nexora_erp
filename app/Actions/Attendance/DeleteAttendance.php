<?php

namespace App\Actions\Attendance;

use App\Models\Attendance;

class DeleteAttendance
{
    /**
     * Delete an attendance record.
     */
    public function execute(Attendance $attendance): void
    {
        $attendance->delete();
    }
}
