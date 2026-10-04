<?php

use App\Models\Attendance;
use App\Models\Branch;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('a user can create an attendance record', function () {
    [$user, $employee, $branch] = attendanceContext();

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/attendances', attendancePayload($employee, $branch))
        ->assertCreated()
        ->assertJsonPath('data.employee_id', $employee->id)
        ->assertJsonPath('data.branch_id', $branch->id)
        ->assertJsonPath('data.status', 'late')
        ->assertJsonPath('data.late_minutes', 25)
        ->assertJsonPath('data.worked_minutes', 480);

    $this->assertDatabaseHas('attendances', [
        'employee_id' => $employee->id,
        'branch_id' => $branch->id,
        'status' => 'late',
        'late_minutes' => 25,
        'overtime_minutes' => 45,
    ]);
});

test('a user can list, filter and view attendance records', function () {
    [$user, $employee, $branch] = attendanceContext();

    $attendance = Attendance::create(attendancePayload($employee, $branch));
    $otherAttendance = Attendance::create(attendancePayload($employee, $branch, [
        'date' => '2026-08-25',
        'status' => 'present',
    ]));

    Sanctum::actingAs($user);

    $this->getJson('/api/v1/attendances')
        ->assertSuccessful()
        ->assertJsonCount(2, 'data.data')
        ->assertJsonPath('data.total', 2);

    $this->getJson("/api/v1/attendances?employee_id={$employee->id}")
        ->assertSuccessful()
        ->assertJsonPath('data.data.0.id', $attendance->id);

    $this->getJson('/api/v1/attendances?branch_id='.$branch->id)
        ->assertSuccessful()
        ->assertJsonCount(2, 'data.data');

    $this->getJson('/api/v1/attendances?status=present')
        ->assertSuccessful()
        ->assertJsonCount(1, 'data.data')
        ->assertJsonPath('data.data.0.id', $otherAttendance->id);

    $this->getJson('/api/v1/attendances?from_date=2026-08-25&to_date=2026-08-25')
        ->assertSuccessful()
        ->assertJsonCount(1, 'data.data')
        ->assertJsonPath('data.data.0.id', $otherAttendance->id);

    $this->getJson('/api/v1/attendances?search='.$employee->employee_code)
        ->assertSuccessful()
        ->assertJsonCount(2, 'data.data');

    $this->getJson('/api/v1/attendances?search=Nobody')
        ->assertSuccessful()
        ->assertJsonCount(0, 'data.data');

    $this->getJson("/api/v1/attendances/{$attendance->id}")
        ->assertSuccessful()
        ->assertJsonPath('data.id', $attendance->id)
        ->assertJsonPath('data.employee.id', $employee->id)
        ->assertJsonPath('data.branch.id', $branch->id);
});

test('a user can update an attendance record', function () {
    [$user, $employee, $branch] = attendanceContext();

    $attendance = Attendance::create(attendancePayload($employee, $branch));

    Sanctum::actingAs($user);

    $this->patchJson("/api/v1/attendances/{$attendance->id}", [
        'branch_id' => $branch->id,
        'date' => '2026-08-24',
        'check_in' => '2026-08-24 09:00:00',
        'check_out' => '2026-08-24 18:30:00',
        'status' => 'half_day',
        'late_minutes' => 0,
        'early_leave_minutes' => 0,
        'worked_minutes' => 240,
        'overtime_minutes' => 0,
        'notes' => 'Half day approved',
    ])
        ->assertSuccessful()
        ->assertJsonPath('data.status', 'half_day')
        ->assertJsonPath('data.worked_minutes', 240)
        ->assertJsonPath('data.notes', 'Half day approved');

    $this->assertDatabaseHas('attendances', [
        'id' => $attendance->id,
        'status' => 'half_day',
        'worked_minutes' => 240,
    ]);
});

test('a user can delete an attendance record', function () {
    [$user, $employee, $branch] = attendanceContext();

    $attendance = Attendance::create(attendancePayload($employee, $branch));

    Sanctum::actingAs($user);

    $this->deleteJson("/api/v1/attendances/{$attendance->id}")
        ->assertSuccessful();

    $this->assertDatabaseMissing('attendances', ['id' => $attendance->id]);
});

test('attendance creation validates its payload', function () {
    [$user, $employee, $branch] = attendanceContext();

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/attendances', [
        'employee_id' => $employee->id,
        'branch_id' => $branch->id,
        'date' => '2026-08-24',
        'status' => 'invalid_status',
        'worked_minutes' => -5,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['status', 'worked_minutes']);

    $this->postJson('/api/v1/attendances', [
        'branch_id' => $branch->id,
        'date' => '2026-08-24',
        'status' => 'present',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['employee_id']);

    $this->assertDatabaseCount('attendances', 0);
});

test('attendance endpoints require authentication', function () {
    $this->getJson('/api/v1/attendances')->assertUnauthorized();
});

/**
 * @return array{0: User, 1: Employee, 2: Branch}
 */
function attendanceContext(): array
{
    $company = Company::factory()->create();
    $branch = Branch::factory()->for($company)->create();

    $user = User::factory()->create();
    CompanyUser::create(['company_id' => $company->id, 'user_id' => $user->id]);

    $employeeUser = User::factory()->create();
    $companyUser = CompanyUser::create([
        'company_id' => $company->id,
        'user_id' => $employeeUser->id,
    ]);

    $employee = Employee::create([
        'company_user_id' => $companyUser->id,
        'branch_id' => $branch->id,
        'employee_code' => 'EMP-ATT-001',
        'first_name' => 'Taylor',
        'last_name' => 'Otwell',
        'joining_date' => '2026-08-24',
        'employment_type' => 'full_time',
    ]);

    return [$user, $employee, $branch];
}

function attendancePayload(Employee $employee, Branch $branch, array $overrides = []): array
{
    return array_merge([
        'employee_id' => $employee->id,
        'branch_id' => $branch->id,
        'date' => '2026-08-24',
        'check_in' => '2026-08-24 09:25:00',
        'check_out' => '2026-08-24 18:10:00',
        'status' => 'late',
        'late_minutes' => 25,
        'early_leave_minutes' => 0,
        'worked_minutes' => 480,
        'overtime_minutes' => 45,
        'notes' => null,
    ], $overrides);
}
