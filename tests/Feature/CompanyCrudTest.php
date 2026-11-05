<?php

use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    syncAccessControl();
});

test('a super admin can list and search companies', function () {
    $superAdmin = userWithRole('super_admin');

    $alpha = Company::factory()->create(['name' => 'Alpha Industries', 'code' => 'CMP-ALPHA']);
    Company::factory()->create(['name' => 'Beta Corp', 'code' => 'CMP-BETA']);

    Sanctum::actingAs($superAdmin);

    $this->getJson('/api/v1/get-company')
        ->assertSuccessful()
        ->assertJsonPath('message', 'Companies fetched successfully')
        ->assertJsonPath('data.total', 2)
        ->assertJsonCount(2, 'data.data');

    $this->getJson('/api/v1/get-company?search=Alpha')
        ->assertSuccessful()
        ->assertJsonPath('data.total', 1)
        ->assertJsonPath('data.data.0.id', $alpha->id);

    $this->getJson('/api/v1/get-company?search=CMP-BETA')
        ->assertSuccessful()
        ->assertJsonPath('data.total', 1);

    $this->getJson('/api/v1/get-company?search=Nothing')
        ->assertSuccessful()
        ->assertJsonPath('data.total', 0);
});

test('a company admin can create a company and is attached to it', function () {
    $admin = userWithRole('company_admin');

    Sanctum::actingAs($admin);

    $this->postJson('/api/v1/create-company', companyPayload())
        ->assertCreated()
        ->assertJsonPath('message', 'Company created successfully')
        ->assertJsonPath('data.name', 'Nexora Holdings')
        ->assertJsonPath('data.code', 'CMP-NEX-001')
        ->assertJsonPath('data.is_active', true);

    $company = Company::query()->where('code', 'CMP-NEX-001')->firstOrFail();

    $this->assertDatabaseHas('company_users', [
        'company_id' => $company->id,
        'user_id' => $admin->id,
    ]);
});

test('company creation validates its payload', function () {
    $admin = userWithRole('company_admin');
    Company::factory()->create(['code' => 'CMP-TAKEN']);

    Sanctum::actingAs($admin);

    $this->postJson('/api/v1/create-company', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'code']);

    $this->postJson('/api/v1/create-company', companyPayload([
        'code' => 'CMP-TAKEN',
        'email' => 'not-an-email',
        'website' => 'not-a-url',
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['code', 'email', 'website']);

    $this->assertDatabaseCount('companies', 1);
});

test('a company member can view their own company', function () {
    $admin = userWithRole('company_admin');
    $company = Company::factory()->create();
    CompanyUser::create(['company_id' => $company->id, 'user_id' => $admin->id]);

    Sanctum::actingAs($admin);

    $this->getJson('/api/v1/my-company')
        ->assertSuccessful()
        ->assertJsonPath('data.id', $company->id)
        ->assertJsonPath('data.code', $company->code);
});

test('my-company returns not found when the user belongs to no company', function () {
    $user = userWithRole('company_admin');

    Sanctum::actingAs($user);

    $this->getJson('/api/v1/my-company')
        ->assertNotFound()
        ->assertJsonPath('message', 'Company not found.');
});

test('a company admin can update and soft delete a company', function () {
    $admin = userWithRole('company_admin');
    $company = Company::factory()->create(['name' => 'Old Name', 'is_active' => true]);

    Sanctum::actingAs($admin);

    $this->patchJson("/api/v1/update-company/{$company->id}", companyPayload([
        'name' => 'New Name',
        'code' => $company->code,
        'is_active' => false,
    ]))
        ->assertSuccessful()
        ->assertJsonPath('data.name', 'New Name')
        ->assertJsonPath('data.is_active', false);

    $this->assertDatabaseHas('companies', [
        'id' => $company->id,
        'name' => 'New Name',
        'is_active' => false,
    ]);

    $this->deleteJson("/api/v1/delete-company/{$company->id}")
        ->assertSuccessful()
        ->assertJsonPath('message', 'Company deleted successfully');

    $this->assertSoftDeleted('companies', ['id' => $company->id]);
});

test('only a super admin can list all companies', function () {
    $admin = userWithRole('company_admin');

    Sanctum::actingAs($admin);

    $this->getJson('/api/v1/get-company')
        ->assertUnauthorized()
        ->assertJsonPath('message', 'Unauthorized');
});

test('a user without company permissions cannot manage companies', function () {
    $user = userWithRole('employee');

    Sanctum::actingAs($user);

    $company = Company::factory()->create();

    $this->postJson('/api/v1/create-company', companyPayload())
        ->assertForbidden();

    $this->patchJson("/api/v1/update-company/{$company->id}", ['name' => 'Blocked'])
        ->assertForbidden();

    $this->deleteJson("/api/v1/delete-company/{$company->id}")
        ->assertForbidden();
});

test('company endpoints require authentication', function () {
    $this->getJson('/api/v1/get-company')->assertUnauthorized();
    $this->postJson('/api/v1/create-company', companyPayload())->assertUnauthorized();
});

/**
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function companyPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Nexora Holdings',
        'code' => 'CMP-NEX-001',
        'legal_name' => 'Nexora Holdings Pvt. Ltd.',
        'registration_number' => 'REG-100200300',
        'tax_number' => 'TAX-400500600',
        'email' => 'hello@nexora.test',
        'phone' => '+977-9800000000',
        'website' => 'https://nexora.test',
        'address' => 'Kathmandu',
        'city' => 'Kathmandu',
        'state' => 'Bagmati',
        'country' => 'Nepal',
        'postal_code' => '44600',
        'is_active' => true,
    ], $overrides);
}
