<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'rank' => 'admin',
            'description' => 'oversees the complete system'
        ]);

        Role::create([
            'rank' => 'registrar',
            'description' => 'responsible for managing the system'
        ]);

        Role::create([
            'rank' => 'lecturer',
            'description' => 'responsible for adding student grades'
        ]);
    }
}
