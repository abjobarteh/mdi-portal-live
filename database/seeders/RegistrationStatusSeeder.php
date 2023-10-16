<?php

namespace Database\Seeders;

use App\Models\RegistrationStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegistrationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RegistrationStatus::create([
            'registration_status' => 0,
        ]);
    }
}
