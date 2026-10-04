<?php

namespace App\Actions\Attendance;

use App\DTO\Attendance\CreateAttendanceDTO;
use App\Models\Attendance;

class CreateAttendance
{
    /**
     * Create a new attendance record.
     */
    public function execute(CreateAttendanceDTO $dto): Attendance
    {
        return Attendance::create($dto->toArray());
    }
}
