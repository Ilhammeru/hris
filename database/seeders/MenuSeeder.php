<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

use function PHPSTORM_META\map;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Menu::truncate();

        Menu::insert([
            [
                'name' => 'dasboard',
                'slug' => 'dashboard',
                'icon' => 'bi-kanban-fill',
                'url' => 'dashboard',
                'role' => '1|2|3',
                'parent' => null,
            ],
            [
                'name' => 'employee',
                'slug' => 'employee',
                'icon' => 'bi-person-badge',
                'url' => 'employee.index',
                'role' => '1|2|3',
                'parent' => null,
            ],
            [
                'name' => 'User Management',
                'slug' => 'user-management',
                'icon' => 'bi-people-fill',
                'url' => '',
                'role' => '1|2|3',
                'parent' => null,
            ],
            [
                'name' => 'user',
                'slug' => 'user',
                'icon' => 'bullet',
                'url' => 'user.list',
                'role' => '1|2|3',
                'parent' => 3
            ],
            [
                'name' => 'roles',
                'slug' => 'roles',
                'icon' => 'bullet',
                'url' => 'user.list',
                'role' => '1|2|3',
                'parent' => 3
            ],
            [
                'name' => 'permission',
                'slug' => 'permission',
                'icon' => 'bullet',
                'url' => 'user.list',
                'role' => '1|2|3',
                'parent' => 3
            ],
            [
                'name' => 'setting',
                'slug' => 'setting',
                'icon' => 'bi-gear-fill',
                'url' => 'setting',
                'role' => '1',
                'parent' => null
            ],
            [
                'name' => 'menu',
                'slug' => 'menu',
                'icon' => 'bullet',
                'url' => 'setting.menu',
                'role' => '1',
                'parent' => 7
            ],
        ]);
    }
}
