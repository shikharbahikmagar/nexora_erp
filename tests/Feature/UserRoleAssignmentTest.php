<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    syncAccessControl();
});

test('an administrator can assign a role to a user', function () {
    $actor = userWithRole('super_admin');
    $target = User::factory()->create();

    Sanctum::actingAs($actor);

    $this->postJson("/api/v1/users/{$target->id}/role", ['role' => 'hr'])
        ->assertSuccessful()
        ->assertJsonPath('message', 'Role assigned successfully.')
        ->assertJsonPath('data.id', $target->id)
        ->assertJsonPath('data.roles.0.name', 'hr');

    expect($target->fresh()->hasRole('hr'))->toBeTrue();
});

test('assigning a role replaces the previous one', function () {
    $actor = userWithRole('super_admin');
    $target = User::factory()->create();
    $target->assignRole('employee');

    Sanctum::actingAs($actor);

    $this->postJson("/api/v1/users/{$target->id}/role", ['role' => 'company_admin'])
        ->assertSuccessful();

    $target->refresh();

    expect($target->hasRole('company_admin'))->toBeTrue()
        ->and($target->hasRole('employee'))->toBeFalse();
});

test('assigning an unknown role fails validation', function () {
    $actor = userWithRole('super_admin');
    $target = User::factory()->create();

    Sanctum::actingAs($actor);

    $this->postJson("/api/v1/users/{$target->id}/role", ['role' => 'does_not_exist'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['role']);

    expect($target->fresh()->roles)->toBeEmpty();
});

test('role assignment requires authentication', function () {
    $target = User::factory()->create();

    $this->postJson("/api/v1/users/{$target->id}/role", ['role' => 'hr'])
        ->assertUnauthorized();
});
