<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SyncRoles extends Command
{
    protected $signature = 'role:sync';

    protected $description = 'Sync roles from configuration';

    public function handle(): int
    {
        $roles = config('roles');

        foreach ($roles as $roleName => $roleConfig) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            if ($roleConfig['permissions'] === '*') {
                $role->syncPermissions(Permission::all());

                continue;
            }

            $role->syncPermissions($roleConfig['permissions']);
        }

        $this->info('Roles synced successfully.');

        return self::SUCCESS;
    }
}
