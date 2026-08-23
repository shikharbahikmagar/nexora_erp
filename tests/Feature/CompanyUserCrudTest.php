<?php

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

test('an authenticated user can manage company users', function () {
    $actor = User::factory()->create();
    $company = Company::factory()->create();
    $member = User::factory()->create();
    $role = Role::create([
        'name' => 'hr',
        'guard_name' => 'web',
    ]);

    Sanctum::actingAs($actor);

    $this->postJson("/api/v1/company-users/{$company->id}", [
        'user_id' => $member->id,
        'role_id' => $role->id,
    ])
        ->assertCreated()
        ->assertJsonPath('data.user.id', $member->id)
        ->assertJsonPath('data.role.id', $role->id);

    $companyUser = CompanyUser::query()->firstOrFail();

    $this->getJson('/api/v1/company-users')
        ->assertSuccessful()
        ->assertJsonPath('data.0.id', $companyUser->id);

    $this->getJson("/api/v1/company-users/{$companyUser->id}")
        ->assertSuccessful()
        ->assertJsonPath('data.id', $companyUser->id);

    $this->patchJson("/api/v1/company-users/{$companyUser->id}", [
        'role_id' => null,
        'is_active' => false,
    ])
        ->assertSuccessful()
        ->assertJsonPath('data.role', null)
        ->assertJsonPath('data.is_active', false);

    $this->assertDatabaseHas('company_users', [
        'id' => $companyUser->id,
        'role_id' => null,
        'is_active' => false,
    ]);

    $this->deleteJson("/api/v1/company-users/{$companyUser->id}")
        ->assertSuccessful();

    $this->assertDatabaseMissing('company_users', [
        'id' => $companyUser->id,
    ]);
});
