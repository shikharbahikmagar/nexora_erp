<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class SyncPermissions extends Command
{
    protected $signature = 'permissions:sync';

    protected $description = 'Sync application permissions';

    public function handle(): int
    {
        $permissions = config('permissions', []);
        $created = 0;

        foreach ($permissions as $resource => $actions) {
            foreach ($actions as $action) {
                $permission = "{$resource}.{$action}";

                $permissionModel = Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => 'web',
                ]);

                if ($permissionModel->wasRecentlyCreated) {
                    $created++;
                }
            }
        }

        $this->info("Permissions synchronized successfully. {$created} new permissions created.");

        return self::SUCCESS;
    }
}
