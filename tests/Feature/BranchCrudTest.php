<?php

use App\Models\Branch;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    syncAccessControl();
});

test('a company admin can list branches of a company', function () {
    $admin = userWithRole('company_admin');
    $company = Company::factory()->create();
    $branch = Branch::factory()->for($company)->create(['name' => 'Kathmandu HQ']);
    Branch::factory()->for(Company::factory())->create();

    Sanctum::actingAs($admin);

    $this->getJson("/api/v1/get-branches/{$company->id}")
        ->assertSuccessful()
        ->assertJsonPath('message', 'Branches Fetched Successfully.')
        ->assertJsonPath('data.total', 1)
        ->assertJsonPath('data.data.0.id', $branch->id);

    $this->getJson("/api/v1/get-branches/{$company->id}?search=Kathmandu")
        ->assertSuccessful()
        ->assertJsonPath('data.total', 1);

    $this->getJson("/api/v1/get-branches/{$company->id}?search=Nowhere")
        ->assertSuccessful()
        ->assertJsonPath('data.total', 0);
});

test('a company admin can create a branch', function () {
    $admin = userWithRole('company_admin');
    $company = Company::factory()->create();

    Sanctum::actingAs($admin);

    $this->postJson('/api/v1/create-branch', branchPayload($company))
        ->assertCreated()
        ->assertJsonPath('message', 'Branch Created Successfully')
        ->assertJsonPath('data.name', 'Pokhara Branch')
        ->assertJsonPath('data.code', 'BR-PKR-001')
        ->assertJsonPath('data.country', 'Nepal')
        ->assertJsonPath('data.timezone', 'Asia/Kathmandu');

    $this->assertDatabaseHas('branches', [
        'company_id' => $company->id,
        'code' => 'BR-PKR-001',
        'is_active' => true,
    ]);
});

test('branch creation validates its payload', function () {
    $admin = userWithRole('company_admin');
    $company = Company::factory()->create();
    Branch::factory()->for($company)->create(['code' => 'BR-DUP']);

    Sanctum::actingAs($admin);

    $this->postJson('/api/v1/create-branch', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['company_id', 'name', 'code']);

    $this->postJson('/api/v1/create-branch', branchPayload($company, [
        'code' => 'BR-DUP',
        'latitude' => 200,
        'longitude' => 500,
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['code', 'latitude', 'longitude']);

    $this->assertDatabaseCount('branches', 1);
});

test('a company admin can update a branch', function () {
    $admin = userWithRole('company_admin');
    $branch = Branch::factory()->for(Company::factory())->create(['name' => 'Old Branch']);

    Sanctum::actingAs($admin);

    $this->patchJson("/api/v1/update-branch/{$branch->id}", [
        'name' => 'Renamed Branch',
        'is_head_office' => true,
    ])
        ->assertSuccessful()
        ->assertJsonPath('data.name', 'Renamed Branch')
        ->assertJsonPath('data.is_head_office', true);

    $this->assertDatabaseHas('branches', [
        'id' => $branch->id,
        'name' => 'Renamed Branch',
        'is_head_office' => true,
    ]);
});

test('a company admin can soft delete a branch', function () {
    $admin = userWithRole('company_admin');
    $branch = Branch::factory()->for(Company::factory())->create();

    Sanctum::actingAs($admin);

    $this->deleteJson("/api/v1/delete-branch/{$branch->id}")
        ->assertSuccessful()
        ->assertJsonPath('message', 'Branch Deleted Successfully');

    $this->assertSoftDeleted('branches', ['id' => $branch->id]);
});

test('a user with view-only branch access cannot write branches', function () {
    $employee = userWithRole('employee');
    $branch = Branch::factory()->for(Company::factory())->create();

    Sanctum::actingAs($employee);

    $this->postJson('/api/v1/create-branch', branchPayload($branch->company))
        ->assertForbidden();

    $this->patchJson("/api/v1/update-branch/{$branch->id}", ['name' => 'Blocked'])
        ->assertForbidden();

    $this->deleteJson("/api/v1/delete-branch/{$branch->id}")
        ->assertForbidden();
});

test('branch endpoints require authentication', function () {
    $branch = Branch::factory()->for(Company::factory())->create();

    $this->getJson("/api/v1/get-branches/{$branch->company_id}")->assertUnauthorized();
    $this->getJson("/api/v1/branch/{$branch->id}")->assertUnauthorized();
});

/**
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function branchPayload(Company $company, array $overrides = []): array
{
    return array_merge([
        'company_id' => $company->id,
        'name' => 'Pokhara Branch',
        'code' => 'BR-PKR-001',
        'email' => 'pokhara@nexora.test',
        'phone' => '+977-9800000001',
        'address' => 'Lakeside',
        'city' => 'Pokhara',
        'state' => 'Gandaki',
        'country' => 'Nepal',
        'postal_code' => '33700',
        'latitude' => 28.2096,
        'longitude' => 83.9856,
        'manager_name' => 'Hari Bahadur',
        'timezone' => 'Asia/Kathmandu',
        'is_head_office' => false,
        'is_active' => true,
        'description' => 'A regional branch.',
    ], $overrides);
}
