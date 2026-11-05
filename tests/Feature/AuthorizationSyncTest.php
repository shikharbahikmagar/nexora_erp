<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

test('the permissions sync command creates every configured permission', function () {
    $expected = collect(config('permissions'))
        ->flatMap(fn (array $actions, string $resource): array => array_map(
            fn (string $action): string => "{$resource}.{$action}",
            $actions
        ))
        ->values();

    $this->artisan('permissions:sync')
        ->expectsOutput('Permissions synchronized successfully. '.$expected->count().' new permissions created.')
        ->assertSuccessful();

    expect(Permission::count())->toBe($expected->count());

    foreach ($expected as $permission) {
        $this->assertDatabaseHas('permissions', [
            'name' => $permission,
            'guard_name' => 'web',
        ]);
    }
});

test('the permissions sync command is idempotent', function () {
    Artisan::call('permissions:sync');
    $firstCount = Permission::count();

    Artisan::call('permissions:sync');

    expect(Permission::count())->toBe($firstCount)
        ->and(Artisan::output())->toContain('0 new permissions created');
});

test('the roles sync command creates roles with their configured permissions', function () {
    Artisan::call('permissions:sync');

    $this->artisan('role:sync')
        ->expectsOutput('Roles synced successfully.')
        ->assertSuccessful();

    expect(Role::pluck('name')->all())
        ->toEqualCanonicalizing(array_keys(config('roles')));

    $superAdmin = Role::findByName('super_admin', 'web');
    expect($superAdmin->permissions()->count())->toBe(Permission::count());

    $companyAdmin = Role::findByName('company_admin', 'web');
    expect($companyAdmin->permissions->pluck('name')->sort()->values()->all())
        ->toBe(collect(config('roles.company_admin.permissions'))->sort()->values()->all());

    $employee = Role::findByName('employee', 'web');
    expect($employee->permissions->pluck('name')->sort()->values()->all())
        ->toBe(collect(config('roles.employee.permissions'))->sort()->values()->all());
});

test('the roles sync command can be run twice without creating duplicates', function () {
    Artisan::call('permissions:sync');
    Artisan::call('role:sync');
    $count = Role::count();

    Artisan::call('role:sync');

    expect(Role::count())->toBe($count);
});
