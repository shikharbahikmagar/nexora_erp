<?php

namespace App\DTO\Attendance;

use App\Enums\AttendanceStatus;

final readonly class UpdateAttendanceDTO
{
    public function __construct(
        public int $branchId,
        public string $date,
        public ?string $checkIn,
        public ?string $checkOut,
        public AttendanceStatus $status,
        public int $lateMinutes,
        public int $earlyLeaveMinutes,
        public int $workedMinutes,
        public int $overtimeMinutes,
        public ?string $notes,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            branchId: (int) $data['branch_id'],
            date: $data['date'],
            checkIn: $data['check_in'] ?? null,
            checkOut: $data['check_out'] ?? null,
            status: AttendanceStatus::from($data['status']),
            lateMinutes: (int) ($data['late_minutes'] ?? 0),
            earlyLeaveMinutes: (int) ($data['early_leave_minutes'] ?? 0),
            workedMinutes: (int) ($data['worked_minutes'] ?? 0),
            overtimeMinutes: (int) ($data['overtime_minutes'] ?? 0),
            notes: $data['notes'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'branch_id' => $this->branchId,
            'date' => $this->date,
            'check_in' => $this->checkIn,
            'check_out' => $this->checkOut,
            'status' => $this->status->value,
            'late_minutes' => $this->lateMinutes,
            'early_leave_minutes' => $this->earlyLeaveMinutes,
            'worked_minutes' => $this->workedMinutes,
            'overtime_minutes' => $this->overtimeMinutes,
            'notes' => $this->notes,
        ];
    }
}
