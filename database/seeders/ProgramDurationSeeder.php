<?php

namespace Database\Seeders;

use App\Models\ProgramDuration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramDurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProgramDuration::create([
            'duration' => '2',
            'description' => 'years',
        ]);
    }
}
