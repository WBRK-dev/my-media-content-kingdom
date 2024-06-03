<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Role;
use App\Models\Permission;

class RolePermMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roleperm:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates all roles and permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $roles = config("laratrust.roles");
        $perms = config("laratrust.permissions");

        $dbPerms = [];

        for ($i=0; $i < count($perms); $i++) { 
            $dbPerms[$perms[$i]["name"]] = Permission::create($perms[$i]);
        }

        for ($i=0; $i < count($roles); $i++) { 
            $role = Role::create($roles[$i]);

            for ($j=0; $j < count($roles[$i]["perms"]); $j++) { 
                $role->givePermission($dbPerms[$roles[$i]["perms"][$j]]);
            }
        }
    }
}
