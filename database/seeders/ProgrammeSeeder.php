<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgrammeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Program::create([
            'name' => 'National Diploma in Banking & Finance',
            'program_abbreviation' => 'ND',
            'fee' => 40500,
            'department_id' => 1,
            'program_duration_id' => 1,

        ]);

        Program::create([
            'name' => 'Higher National Diploma in Banking & Finance',
            'program_abbreviation' => 'HND',
            'fee' => 50500,
            'department_id' => 1,
            'program_duration_id' => 1,
        ]);
    }
}
