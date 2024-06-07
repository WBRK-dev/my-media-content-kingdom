<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RolePermAssign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roleperm:assign 
                            {--u|user= : The user ID to assign the role or permission to} 
                            {--r|role= : The role name to assign to the user} 
                            {--p|permission= : The permission name to assign to the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assigns a role or permission to a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option("user") === null) { $this->error("User ID is required"); return; }
        if ($this->option("role") === null && $this->option("permission") === null) { $this->error("Define one of the following: --role, --permission"); return; }
        if ($this->option("role") !== null && $this->option("permission") !== null) { $this->error("Only define one of the following: --role, --permission"); return; }
        
        $this->line("Getting user...");

        $user = null;
        try {
            $user = User::findOrFail($this->option("user"));
        } catch (\Throwable $th) {
            $this->error("User not found");
            return;
        }

        if ($this->option("role") !== null) {
            $this->line("Getting role...");
            $role = Role::where("name", $this->option("role"))->first();
            if ($role === null) {
                $this->error("Role not found");
                return;
            }

            $user->addRole($role);
            $this->line("Gave role " . $role->display_name . " to " . $user->name);
        } else {
            $this->line("Getting permission...");
            $perm = Permission::where("name", $this->option("permission"))->first();
            if ($perm === null) {
                $this->error("Permission not found");
                return;
            }

            $user->givePermission($perm);
            $this->line("Gave permission " . $perm->display_name . " to " . $user->name);
        }
    }
}
