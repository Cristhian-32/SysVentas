<?php

namespace Database\Seeders;

use App\Models\User;
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
        User::create([
            'name'        => 'Francisco Morales',
            'phone'        => '982166481',
            'email'       => 'qwe@qwe',
            'profile'     => 'ADMIN',
            'status'       => 'ACTIVE',
            'password'      => bcrypt('1234'),
        ]);
        User::create([
            'name'        => 'Roberto Perez',
            'phone'        => '9562166445',
            'email'       => 'rperez@example.com',
            'profile'     => 'EMPLOYEE',
            'status'       => 'ACTIVE',
            'password'      => bcrypt('password'),
        ]);

    }
}
