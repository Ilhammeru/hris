<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PermissionGroup::truncate();

        PermissionGroup::insert([
            [
                'name' => 'Employee',
            ],
            [
                'name' => 'Report',
            ],
            [
                'name' => 'Calendar',
            ],
        ]);

        Permission::create(['name' => 'show-employee', 'permission_group_id' => 1]);
        Permission::create(['name' => 'create-employee', 'permission_group_id' => 1]);
        Permission::create(['name' => 'update-employee', 'permission_group_id' => 1]);
        Permission::create(['name' => 'delete-employee', 'permission_group_id' => 1]);
        Permission::create(['name' => 'report-all-employee', 'permission_group_id' => 1]);
        Permission::create(['name' => 'report-employee-per-division', 'permission_group_id' => 1]);
    }
}
