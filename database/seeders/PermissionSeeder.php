<?php

namespace Database\Seeders;

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
        Permission::create(['name' => 'show-employee']);
        Permission::create(['name' => 'create-employee']);
        Permission::create(['name' => 'update-employee']);
        Permission::create(['name' => 'delete-employee']);
        Permission::create(['name' => 'report-all-employee']);
        Permission::create(['name' => 'report-employee-per-division']);
    }
}
