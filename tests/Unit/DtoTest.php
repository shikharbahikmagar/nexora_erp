<?php

use App\DTO\Attendance\CreateAttendanceDTO;
use App\DTO\Branch\CreateBranchDTO;
use App\DTO\Branch\UpdateBranchDTO;
use App\DTO\Company\CreateCompanyDTO;
use App\DTO\Company\UpdateCompanyDTO;
use App\DTO\CompanyUser\CreateCompanyUserDTO;
use App\DTO\CompanyUser\UpdateCompanyUserDTO;
use App\DTO\Employee\CreateEmployeeDocumentDTO;
use App\DTO\Employee\UpdateEmployeeDTO;
use App\DTO\User\AssignRoleDTO;
use App\Enums\AttendanceStatus;

test('create company dto maps snake_case input and back', function () {
    $dto = CreateCompanyDTO::fromArray([
        'name' => 'Nexora',
        'code' => 'CMP-001',
        'legal_name' => 'Nexora Pvt. Ltd.',
        'postal_code' => '44600',
        'is_active' => false,
    ]);

    expect($dto->name)->toBe('Nexora')
        ->and($dto->code)->toBe('CMP-001')
        ->and($dto->legalName)->toBe('Nexora Pvt. Ltd.')
        ->and($dto->postalCode)->toBe('44600')
        ->and($dto->isActive)->toBeFalse()
        ->and($dto->toArray()['legal_name'])->toBe('Nexora Pvt. Ltd.');
});

test('create company dto defaults optional flags', function () {
    $dto = CreateCompanyDTO::fromArray(['name' => 'Nexora', 'code' => 'CMP-001']);

    expect($dto->isActive)->toBeTrue()
        ->and($dto->email)->toBeNull();
});

test('update company dto keeps only the values supplied', function () {
    $dto = UpdateCompanyDTO::fromArray(['name' => 'Renamed', 'is_active' => false]);

    expect($dto->name)->toBe('Renamed')
        ->and($dto->isActive)->toBeFalse()
        ->and($dto->code)->toBeNull()
        ->and($dto->toArray())->toHaveKey('name')
        ->and($dto->toArray()['code'])->toBeNull();
});

test('create branch dto applies application defaults', function () {
    $dto = CreateBranchDTO::fromArray([
        'company_id' => 1,
        'name' => 'HQ',
        'code' => 'BR-001',
    ]);

    expect($dto->country)->toBe('Nepal')
        ->and($dto->timezone)->toBe('Asia/Kathmandu')
        ->and($dto->is_head_office)->toBeFalse()
        ->and($dto->is_active)->toBeTrue()
        ->and($dto->toArray()['company_id'])->toBe(1);
});

test('update branch dto removes null values from its payload', function () {
    $dto = UpdateBranchDTO::fromArray(['name' => 'Renamed', 'city' => null]);

    expect($dto->toArray())->toBe(['name' => 'Renamed']);
});

test('company user dtos map their payloads', function () {
    $create = CreateCompanyUserDTO::fromArray(['user_id' => 7, 'is_active' => false]);
    $update = UpdateCompanyUserDTO::fromArray(['is_active' => false]);

    expect($create->userId)->toBe(7)
        ->and($create->isActive)->toBeFalse()
        ->and($update->toArray())->toBe(['is_active' => false]);
});

test('employee document dtos map their payloads', function () {
    $dto = CreateEmployeeDocumentDTO::fromRequest(3, [
        'document_type' => 'passport',
        'document' => 'documents/passport.pdf',
    ]);

    expect($dto->employee_id)->toBe(3)
        ->and($dto->document_type)->toBe('passport')
        ->and($dto->document)->toBe('documents/passport.pdf');
});

test('employment dtos pass their payload through', function () {
    $dto = UpdateEmployeeDTO::fromArray(['employment_status' => 'on_leave']);

    expect($dto->toArray())->toBe(['employment_status' => 'on_leave']);
});

test('assign role dto maps the role', function () {
    $dto = AssignRoleDTO::fromArray(['role' => 'hr']);

    expect($dto->role)->toBe('hr');
});

test('attendance dto casts the status enum and numeric defaults', function () {
    $dto = CreateAttendanceDTO::fromArray([
        'employee_id' => '4',
        'branch_id' => '9',
        'date' => '2026-08-24',
        'status' => 'late',
        'late_minutes' => '15',
    ]);

    expect($dto->employeeId)->toBe(4)
        ->and($dto->branchId)->toBe(9)
        ->and($dto->status)->toBe(AttendanceStatus::LATE)
        ->and($dto->lateMinutes)->toBe(15)
        ->and($dto->workedMinutes)->toBe(0)
        ->and($dto->notes)->toBeNull()
        ->and($dto->toArray()['status'])->toBe('late');
});
