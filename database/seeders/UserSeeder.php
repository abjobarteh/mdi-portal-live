<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'firstname' => 'Admin',
            'middlename' => 'A',
            'lastname' => 'admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'address' => '123 Main St',
            'role_id' => 1,
            'phonenumber' => '7936410',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'firstname' => 'Registrar',
            'middlename' => 'R',
            'lastname' => 'registrar',
            'username' => 'registrar',
            'email' => 'registrar@gmail.com',
            'address' => '123 Main St',
            'phonenumber' => '7018247',
            'role_id' => 2,
            'password' => Hash::make('password'),
        ]);

        User::create([
            'firstname' => 'Student',
            'middlename' => 'R',
            'lastname' => 'student',
            'username' => 'student',
            'email' => 'student@gmail.com',
            'address' => '123 Main St',
            'phonenumber' => '7199012',
            'role_id' => 4,  // student is having the role_of 5
            'password' => Hash::make('password'),
        ]);
    }
}
