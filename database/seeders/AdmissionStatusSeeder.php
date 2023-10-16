<?php

namespace Database\Seeders;

use App\Models\AdmissionStatus;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdmissionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdmissionStatus::create([
            'admission_status' => 0,
        ]);
    }
}
