<?php

namespace App\Actions\Attendance;

use App\DTO\Attendance\UpdateAttendanceDTO;
use App\Models\Attendance;

class UpdateAttendance
{
    /**
     * Update an existing attendance record.
     */
    public function execute(Attendance $attendance, UpdateAttendanceDTO $dto): Attendance
    {
        $attendance->update($dto->toArray());

        return $attendance->refresh();
    }
}
