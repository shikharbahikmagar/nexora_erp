<?php

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('an authenticated user can create and manage company users', function () {
    $actor = User::factory()->create();
    $company = Company::factory()->create();

    Sanctum::actingAs($actor);

    $this->postJson("/api/v1/company-users/{$company->id}", [
        'name' => 'New Member',
        'email' => 'member@nexora.test',
        'password' => 'password123',
    ])
        ->assertCreated()
        ->assertJsonPath('data.user.name', 'New Member')
        ->assertJsonPath('data.user.email', 'member@nexora.test')
        ->assertJsonPath('data.is_active', true);

    $member = User::query()->where('email', 'member@nexora.test')->firstOrFail();

    expect(Hash::check('password123', $member->password))->toBeTrue();

    $companyUser = CompanyUser::query()->firstOrFail();

    $this->getJson('/api/v1/company-users')
        ->assertSuccessful()
        ->assertJsonPath('data.0.id', $companyUser->id);

    $this->getJson("/api/v1/company-users/{$companyUser->id}")
        ->assertSuccessful()
        ->assertJsonPath('data.id', $companyUser->id);

    $this->patchJson("/api/v1/company-users/{$companyUser->id}", [
        'is_active' => false,
    ])
        ->assertSuccessful()
        ->assertJsonPath('data.is_active', false);

    $this->assertDatabaseHas('company_users', [
        'id' => $companyUser->id,
        'is_active' => false,
    ]);

    $this->deleteJson("/api/v1/company-users/{$companyUser->id}")
        ->assertSuccessful();

    $this->assertDatabaseMissing('company_users', [
        'id' => $companyUser->id,
    ]);
});

test('creating a company user reuses an existing user without changing their credentials', function () {
    $actor = User::factory()->create();
    $company = Company::factory()->create();
    $existing = User::factory()->create([
        'name' => 'Existing Name',
        'email' => 'existing@nexora.test',
        'password' => Hash::make('original-password'),
    ]);
    $originalPassword = $existing->password;

    Sanctum::actingAs($actor);

    $this->postJson("/api/v1/company-users/{$company->id}", [
        'name' => 'Changed Name',
        'email' => 'existing@nexora.test',
        'password' => 'new-password-123',
        'is_active' => false,
    ])
        ->assertCreated()
        ->assertJsonPath('data.user.id', $existing->id)
        ->assertJsonPath('data.user.name', 'Existing Name')
        ->assertJsonPath('data.is_active', false);

    $existing->refresh();

    expect($existing->name)->toBe('Existing Name')
        ->and($existing->password)->toBe($originalPassword)
        ->and(Hash::check('original-password', $existing->password))->toBeTrue()
        ->and(Hash::check('new-password-123', $existing->password))->toBeFalse();

    $this->assertDatabaseHas('company_users', [
        'company_id' => $company->id,
        'user_id' => $existing->id,
        'is_active' => false,
    ]);
});

test('a duplicate company membership is rejected', function () {
    $actor = User::factory()->create();
    $company = Company::factory()->create();
    $existing = User::factory()->create(['email' => 'existing@nexora.test']);

    CompanyUser::create([
        'company_id' => $company->id,
        'user_id' => $existing->id,
    ]);

    Sanctum::actingAs($actor);

    $this->postJson("/api/v1/company-users/{$company->id}", [
        'name' => 'Existing Name',
        'email' => 'existing@nexora.test',
        'password' => 'password123',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);

    $this->assertDatabaseCount('company_users', 1);
});

test('company user creation validates its payload', function () {
    $actor = User::factory()->create();
    $company = Company::factory()->create();

    Sanctum::actingAs($actor);

    $this->postJson("/api/v1/company-users/{$company->id}", [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'email', 'password']);

    $this->postJson("/api/v1/company-users/{$company->id}", [
        'name' => 'Invalid Member',
        'email' => 'not-an-email',
        'password' => 'short',
        'is_active' => 'not-a-boolean',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email', 'password', 'is_active']);

    $this->assertDatabaseCount('users', 1);
});
