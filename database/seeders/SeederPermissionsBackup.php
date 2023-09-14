<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SeederPermissionsBackup extends Seeder
{
    /**
     * Run the database seeds.
     */

    static $permissions = [
        'create-backup',
        'delete-backup',
        'download-backup',
        'view-backup'
    ];

    static $roles = [
        'admin',
        'manager',
    ];



    public function run(): void
    {
        foreach (self::$permissions as $permission) {
            DB::table('permissions')->insert([
                'name' => $permission,
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        foreach (self::$permissions as $permission) {
            $permissionDB = DB::table('permissions')->select(['id'])->where('name', '=', $permission)->first();
            foreach (self::$roles as $role) {
                $roleDB = DB::table('roles')->select(['id'])->where('name', '=', $role)->first();
                DB::table('role_has_permissions')->insert([
                    'permission_id' => $permissionDB->id,
                    'role_id' => $roleDB->id,
                ]);
            }    
        }
    }
}
