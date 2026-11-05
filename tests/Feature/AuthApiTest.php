<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    syncAccessControl();
});

test('a new user can register and receives an api token', function () {
    $response = $this->postJson('/api/v1/auth/register', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => 'secret-password',
        'password_confirmation' => 'secret-password',
    ]);

    $response
        ->assertCreated()
        ->assertJsonPath('message', 'Registration successful.')
        ->assertJsonPath('data.user.name', 'Jane Doe')
        ->assertJsonPath('data.user.email', 'jane@example.com')
        ->assertJsonStructure(['data' => ['user' => ['id', 'name', 'email'], 'token']]);

    $user = User::query()->where('email', 'jane@example.com')->firstOrFail();

    expect($user->hasRole('company_admin'))->toBeTrue()
        ->and($user->tokens()->count())->toBe(1);
});

test('registration validates its payload', function () {
    $this->postJson('/api/v1/auth/register', [
        'name' => '',
        'email' => 'not-an-email',
        'password' => 'short',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'email', 'password']);

    User::factory()->create(['email' => 'existing@example.com']);

    $this->postJson('/api/v1/auth/register', [
        'name' => 'Jane Doe',
        'email' => 'existing@example.com',
        'password' => 'secret-password',
        'password_confirmation' => 'secret-password',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

test('a user can log in and receives an httpOnly auth cookie', function () {
    $user = User::factory()->create([
        'email' => 'login@example.com',
        'password' => 'secret-password',
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'secret-password',
    ]);

    $response
        ->assertSuccessful()
        ->assertJsonPath('message', 'Login successful.')
        ->assertJsonPath('data.user.id', $user->id)
        ->assertJsonStructure(['data' => ['token']])
        ->assertCookie('auth_token');

    $cookie = $response->getCookie('auth_token', false);

    expect($cookie)->not->toBeNull()
        ->and($cookie->isHttpOnly())->toBeTrue();
});

test('login rejects invalid credentials', function () {
    User::factory()->create([
        'email' => 'login@example.com',
        'password' => 'secret-password',
    ]);

    $this->postJson('/api/v1/auth/login', [
        'email' => 'login@example.com',
        'password' => 'wrong-password',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);

    $this->postJson('/api/v1/auth/login', [
        'email' => 'missing@example.com',
        'password' => 'secret-password',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

test('an authenticated user can fetch their profile', function () {
    $user = userWithRole('company_admin');

    Sanctum::actingAs($user);

    $this->getJson('/api/v1/auth/me')
        ->assertSuccessful()
        ->assertJsonPath('data.id', $user->id)
        ->assertJsonPath('data.name', $user->name)
        ->assertJsonPath('data.email', $user->email)
        ->assertJsonPath('data.role', 'company_admin');
});

test('a user can log out and their access token is revoked', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    $this->withHeader('Authorization', "Bearer {$token}")
        ->postJson('/api/v1/auth/logout')
        ->assertSuccessful()
        ->assertJsonPath('message', 'Logged out successfully.');

    $this->assertDatabaseCount('personal_access_tokens', 0);
});

test('profile and logout endpoints require authentication', function () {
    $this->getJson('/api/v1/auth/me')->assertUnauthorized();
    $this->postJson('/api/v1/auth/logout')->assertUnauthorized();
});

test('the company_admin role exists for new registrations', function () {
    expect(Role::query()->where('name', 'company_admin')->exists())->toBeTrue();
});
