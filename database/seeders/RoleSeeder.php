<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            Role::create(['name' => 'manager']);
            Role::create(['name' => 'hrd']);
            Role::create(['name' => 'finance']);
            Role::create(['name' => 'it']);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
