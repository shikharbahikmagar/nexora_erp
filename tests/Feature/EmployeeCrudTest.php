<?php

use App\Models\Branch;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    $permissions = collect(['view', 'create', 'update', 'delete'])
        ->map(fn (string $action): Permission => Permission::create([
            'name' => "employees.{$action}",
            'guard_name' => 'web',
        ]));

    $companyAdminRole = Role::create(['name' => 'company_admin', 'guard_name' => 'web']);
    $companyAdminRole->syncPermissions($permissions);

    $superAdminRole = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
    $superAdminRole->syncPermissions($permissions);
});

test('a company admin can manage employees in their company', function () {
    $company = Company::factory()->create();
    $branch = Branch::factory()->for($company)->create();
    $admin = User::factory()->create();
    $admin->assignRole('company_admin');
    CompanyUser::create(['company_id' => $company->id, 'user_id' => $admin->id]);

    $employeeUser = User::factory()->create();
    $employeeCompanyUser = CompanyUser::create([
        'company_id' => $company->id,
        'user_id' => $employeeUser->id,
    ]);

    Sanctum::actingAs($admin);

    $this->postJson('/api/v1/employees', employeePayload($employeeCompanyUser, $branch))
        ->assertCreated()
        ->assertJsonPath('data.company_user.id', $employeeCompanyUser->id)
        ->assertJsonPath('data.branch.id', $branch->id);

    $employee = Employee::query()->firstOrFail();

    $this->getJson('/api/v1/employees')
        ->assertSuccessful()
        ->assertJsonPath('data.0.id', $employee->id);

    $this->getJson("/api/v1/employees/{$employee->id}")
        ->assertSuccessful()
        ->assertJsonPath('data.id', $employee->id);

    $this->patchJson("/api/v1/employees/{$employee->id}", [
        'employment_status' => 'on_leave',
        'middle_name' => null,
    ])
        ->assertSuccessful()
        ->assertJsonPath('data.employment_status', 'on_leave');

    $this->assertDatabaseHas('employees', [
        'id' => $employee->id,
        'employment_status' => 'on_leave',
    ]);

    $this->deleteJson("/api/v1/employees/{$employee->id}")
        ->assertSuccessful();

    $this->assertSoftDeleted('employees', ['id' => $employee->id]);
});

test('a company admin cannot access employees from another company', function () {
    $company = Company::factory()->create();
    $admin = User::factory()->create();
    $admin->assignRole('company_admin');
    CompanyUser::create(['company_id' => $company->id, 'user_id' => $admin->id]);

    $otherCompany = Company::factory()->create();
    $otherBranch = Branch::factory()->for($otherCompany)->create();
    $otherEmployeeUser = User::factory()->create();
    $otherEmployeeCompanyUser = CompanyUser::create([
        'company_id' => $otherCompany->id,
        'user_id' => $otherEmployeeUser->id,
    ]);
    $otherEmployee = Employee::create(employeePayload($otherEmployeeCompanyUser, $otherBranch));

    Sanctum::actingAs($admin);

    $this->getJson('/api/v1/employees')
        ->assertSuccessful()
        ->assertJsonCount(0, 'data');

    $this->getJson("/api/v1/employees/{$otherEmployee->id}")->assertForbidden();
    $this->patchJson("/api/v1/employees/{$otherEmployee->id}", ['first_name' => 'Blocked'])->assertForbidden();
    $this->deleteJson("/api/v1/employees/{$otherEmployee->id}")->assertForbidden();
    $this->postJson('/api/v1/employees', employeePayload($otherEmployeeCompanyUser, $otherBranch, 'EMP-CROSS-COMPANY'))
        ->assertForbidden();
});

test('a super admin can access employees from every company', function () {
    $company = Company::factory()->create();
    $branch = Branch::factory()->for($company)->create();
    $employeeUser = User::factory()->create();
    $employeeCompanyUser = CompanyUser::create([
        'company_id' => $company->id,
        'user_id' => $employeeUser->id,
    ]);
    $employee = Employee::create(employeePayload($employeeCompanyUser, $branch));

    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super_admin');
    Sanctum::actingAs($superAdmin);

    $this->getJson('/api/v1/employees')
        ->assertSuccessful()
        ->assertJsonPath('data.0.id', $employee->id);

    $this->patchJson("/api/v1/employees/{$employee->id}", ['first_name' => 'Updated'])
        ->assertSuccessful()
        ->assertJsonPath('data.first_name', 'Updated');
});

function employeePayload(CompanyUser $companyUser, Branch $branch, string $employeeCode = 'EMP-001'): array
{
    return [
        'company_user_id' => $companyUser->id,
        'branch_id' => $branch->id,
        'employee_code' => $employeeCode,
        'first_name' => 'Taylor',
        'last_name' => 'Otwell',
        'joining_date' => '2026-08-24',
        'employment_type' => 'full_time',
    ];
}
