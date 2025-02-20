<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(ProgramDurationSeeder::class);
        $this->call(ProgrammeSeeder::class);
        $this->call(CourseSeeder::class);
        $this->call(GradingSystemSeeder::class);
        $this->call(RegistrationStatusSeeder::class);
        $this->call(AdmissionStatusSeeder::class);
    }
}
