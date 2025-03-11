<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewRegistrarRoles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'rank' => 'Registrar Clerk',
            'description' => 'Responsible for the academic administration'
        ]);

        Role::create([
            'rank' => 'Registrar Assistant',
            'description' => 'Responsible for the academic administration'
        ]);
    }
}
