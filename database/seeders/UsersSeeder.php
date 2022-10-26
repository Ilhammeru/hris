<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $user = [
            [
                'email' => 'admin@gmail.com',
                'password' => Hash::make('ilhammeru'),
                'role' => Role::findByName('manager')->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'email' => 'hr@gmail.com',
                'password' => Hash::make('ilhammeru'),
                'role' => Role::findByName('hrd')->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        User::insert($user);
        
        $hr = User::where('email', 'hr@gmail.com')
            ->first();
        $hr->assignRole(Role::findByName('hrd')->name);

        $admin = User::where('email', 'admin@gmail.com')
            ->first();
        $admin->assignRole(Role::findByName('manager')->name);

        $hrRole = Role::findByName('hrd');
        $adminRole = Role::findByName('manager');
        $permissionHrd = Permission::where('name', 'show-employee')
            ->orWhere('name', 'create-employee')
            ->orWhere('name', 'update-employee')
            ->orWhere('name', 'delete-employee')
            ->orWhere('name', 'report-all-employee')
            ->get();
        $hrRole->syncPermissions($permissionHrd);
        $adminRole->syncPermissions($permissionHrd);
    }
}
