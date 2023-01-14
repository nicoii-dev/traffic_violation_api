<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'firstname' => 'Lance',
            'lastname' => 'Williams',
            'name' => 'Lance Williams',
            'email' => 'admin@admin.com',
            'password' => bcrypt('Default123'),
            'is_verified' => true
        ]);
        $user->assignRole('Admin');

        $user = User::create([
            'firstname' => 'Nibble',
            'lastname' => 'Devs',
            'name' => 'Nibble Devs(access)',
            'email' => 'admin@nibble.com',
            'password' => bcrypt('Default123'),
            'is_verified' => true
        ]);
        $user->assignRole('Admin');
    }
}
