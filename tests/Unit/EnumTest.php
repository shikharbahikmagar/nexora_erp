<?php

use App\Enums\AttendanceStatus;
use App\Enums\EmployeeContractStatus;
use App\Enums\EmployeeContractType;
use App\Enums\EmploymentStatus;
use App\Enums\EmploymentType;
use App\Enums\SalaryType;

test('attendance status enum exposes the expected values', function () {
    expect(array_column(AttendanceStatus::cases(), 'value'))->toBe([
        'present',
        'absent',
        'late',
        'half_day',
        'on_leave',
    ]);

    expect(AttendanceStatus::tryFrom('late'))->toBe(AttendanceStatus::LATE)
        ->and(AttendanceStatus::tryFrom('invalid'))->toBeNull();
});

test('employee contract enums expose the expected values', function () {
    expect(array_column(EmployeeContractType::cases(), 'value'))->toBe([
        'permanent',
        'temporary',
        'internship',
        'freelance',
        'part_time',
        'contract',
    ]);

    expect(array_column(EmployeeContractStatus::cases(), 'value'))->toBe([
        'draft',
        'active',
        'expired',
        'terminated',
    ]);
});

test('employment enums expose the expected values', function () {
    expect(array_column(EmploymentType::cases(), 'value'))->toBe([
        'full_time',
        'part_time',
        'internship',
        'freelance',
    ]);

    expect(array_column(EmploymentStatus::cases(), 'value'))->toBe([
        'active',
        'probation',
        'on_leave',
        'suspended',
        'notice_period',
        'terminated',
        'resigned',
        'retired',
    ]);
});

test('salary type enum exposes the expected values', function () {
    expect(array_column(SalaryType::cases(), 'value'))->toBe([
        'hourly',
        'daily',
        'monthly',
        'annual',
    ]);
});
