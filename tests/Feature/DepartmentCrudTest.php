<?php

use App\Models\Company;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    syncAccessControl();
});

test('a company admin can list departments for a company', function () {
    $admin = userWithRole('company_admin');
    $company = Company::factory()->create();
    $department = Department::create([
        'company_id' => $company->id,
        'name' => 'Human Resources',
        'code' => 'HR',
        'is_active' => true,
    ]);
    Department::create([
        'company_id' => Company::factory()->create()->id,
        'name' => 'Finance',
        'code' => 'FIN',
    ]);

    Sanctum::actingAs($admin);

    $this->getJson("/api/v1/departments/{$company->id}")
        ->assertSuccessful()
        ->assertJsonPath('message', 'Departments Fetched Successfully.')
        ->assertJsonPath('data.total', 1)
        ->assertJsonPath('data.data.0.id', $department->id);

    $this->getJson("/api/v1/departments/{$company->id}?search=Human")
        ->assertSuccessful()
        ->assertJsonPath('data.total', 1);

    $this->getJson("/api/v1/departments/{$company->id}?search=Missing")
        ->assertSuccessful()
        ->assertJsonPath('data.total', 0);
});

test('a company admin can create a department', function () {
    $admin = userWithRole('company_admin');
    $company = Company::factory()->create();

    Sanctum::actingAs($admin);

    $this->postJson('/api/v1/departments', [
        'company_id' => $company->id,
        'name' => 'Engineering',
        'code' => 'ENG',
        'description' => 'Product engineering team.',
        'is_active' => true,
    ])
        ->assertCreated()
        ->assertJsonPath('message', 'Department Created Successfully.')
        ->assertJsonPath('data.name', 'Engineering')
        ->assertJsonPath('data.code', 'ENG')
        ->assertJsonPath('data.is_active', true);

    $this->assertDatabaseHas('departments', [
        'company_id' => $company->id,
        'name' => 'Engineering',
        'code' => 'ENG',
    ]);
});

test('department creation validates its payload', function () {
    $admin = userWithRole('company_admin');

    Sanctum::actingAs($admin);

    $this->postJson('/api/v1/departments', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['company_id', 'name']);

    $this->postJson('/api/v1/departments', [
        'company_id' => 9999,
        'name' => 'Engineering',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['company_id']);

    $this->assertDatabaseCount('departments', 0);
});

test('a user without department create permission cannot create departments', function () {
    $employee = userWithRole('employee');
    $company = Company::factory()->create();

    Sanctum::actingAs($employee);

    $this->postJson('/api/v1/departments', [
        'company_id' => $company->id,
        'name' => 'Blocked',
    ])->assertForbidden();
});

test('department endpoints require authentication', function () {
    $company = Company::factory()->create();

    $this->getJson("/api/v1/departments/{$company->id}")->assertUnauthorized();
    $this->postJson('/api/v1/departments', [
        'company_id' => $company->id,
        'name' => 'Engineering',
    ])->assertUnauthorized();
});
