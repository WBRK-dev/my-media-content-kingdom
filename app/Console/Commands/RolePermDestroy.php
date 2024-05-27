<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

class RolePermDestroy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roleperm:destroy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes roles, permissions and all relations from database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // DB::select(DB::raw("DELETE FROM permissions"));
        // DB::select(DB::raw("DELETE FROM roles"));
        // DB::select(DB::raw("DELETE FROM permission_role"));
        // DB::select(DB::raw("DELETE FROM role_user"));
        // DB::select(DB::raw("DELETE FROM permission_user"));
        DB::table("permissions")->delete();
        DB::table("roles")->delete();
        DB::table("permission_role")->delete();
        DB::table("role_user")->delete();
        DB::table("permission_user")->delete();
    }
}
