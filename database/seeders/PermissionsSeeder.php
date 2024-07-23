<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        \App\Models\User::create([
            'f_name' => 'Ali ',
            'l_name' => 'msallam ',
            'email' => 'ali@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'admin',
            ]);
            \App\Models\User::create([
                'f_name' => 'Tomy',
                'l_name' => 'Hardy',
                'email' => 'ali2@gmail.com',
                'password' => bcrypt('12345678'),
                'role' => 'chef',
                ]);
            \App\Models\User::create([
                'f_name' => 'Alvaro',
                'l_name' => 'Morte',
                'email' => 'ali3@gmail.com',
                'password' => bcrypt('12345678'),
                'role' => 'user',
                ]);
    }
}