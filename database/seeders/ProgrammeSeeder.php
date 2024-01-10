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
            'name' => 'NATIONAL DIPLOMA IN ACCOUNTANCY ',
            'program_abbreviation' => 'NDA',
            'fee' => 40500,
            'per_semester_fee' => 10125,
            'department_id' => 7,
            'program_duration_id' => 1,

        ])->create([
            'name' => 'NATIONAL DIPLOMA IN BANKING & FINANCE',
            'program_abbreviation' => 'HND',
            'fee' => 40500,
            'per_semester_fee' => 10125,

            'department_id' => 3,
            'program_duration_id' => 1,
        ])->create([
            'name' => 'HIGHER NATIONAL DIPLOMA IN BANKING & FINANCE',
            'program_abbreviation' => 'HND',
            'fee' => 50500,
            'per_semester_fee' => 12625,

            'department_id' => 3,
            'program_duration_id' => 1,
        ])->create([
            'name' => 'NATIONAL DIPLOMA IN DIPLOMACY & INTERNATIONAL RELATIONS',
            'program_abbreviation' => 'NDR',
            'fee' => 40500,
            'per_semester_fee' => 10125,
            'department_id' => 1,
            'program_duration_id' => 1,
        ])->create([
            'name' => 'NATIONAL DIPLOMA IN COMPUTER SCIENCE',
            'program_abbreviation' => 'NDC',
            'fee' => 50500,
            'per_semester_fee' => 12625,

            'department_id' => 4,
            'program_duration_id' => 1,
        ])->create([
            'name' => 'NATIONAL DIPLOMA IN GENDER AND DEVELOPMENT STUDIES',
            'program_abbreviation' => 'NDD',
            'fee' => 40500,
            'per_semester_fee' => 10125,

            'department_id' => 2,
            'program_duration_id' => 1,
        ])->create([
            'name' => 'NATIONAL DIPLOMA IN MANAGEMENT STUDIES',
            'program_abbreviation' => 'HND',
            'fee' => 40500,
            'per_semester_fee' => 10125,

            'department_id' => 6,
            'program_duration_id' => 1,
        ])->create([
            'name' => 'POST GRADUATE IN BANKING & FINANCE',
            'program_abbreviation' => 'PGB',
            'fee' => 65000,
            'per_semester_fee' => 32500,

            'department_id' => 7,
            'program_duration_id' => 2,
        ])->create([
            'name' => 'NATIONAL DIPLOMA IN HR',
            'program_abbreviation' => 'NDH',
            'fee' => 40500,
            'per_semester_fee' => 10125,

            'department_id' => 6,
            'program_duration_id' => 1,
        ])->create([
            'name' => 'HIGHER NATIONAL DIPLOMA IN MANAGEMENT',
            'program_abbreviation' => 'HND',
            'fee' => 50500,
            'per_semester_fee' => 12625,

            'department_id' => 6,
            'program_duration_id' => 1,
        ])->create([
            'name' => 'HIGHER NATIONAL DIPLOMA IN HR',
            'program_abbreviation' => 'HND',
            'fee' => 50500,
            'per_semester_fee' => 12625,

            'department_id' => 6,
            'program_duration_id' => 1,
        ])->create([
            'name' => 'POSTGRADUATE IN PUBLIC ADMIN',
            'program_abbreviation' => 'PGA',
            'fee' => 60500,
            'per_semester_fee' => 30250,
            'department_id' => 6, // IN HR
            'program_duration_id' => 2,
        ])->create([
            'name' => 'HIGHER NATIONAL DIPLOMA IN DIPLOMACY & INTERNATIONAL RELATIONS',
            'program_abbreviation' => 'HND',
            'fee' => 50500,
            'per_semester_fee' => 12625,
            'department_id' => 1,
            'program_duration_id' => 1,
        ])->create([
            'name' => 'Post Graduate in Diplomacy and International Relations',
            'program_abbreviation' => 'PGD',
            'fee' => 65000,
            'per_semester_fee' => 32500,
            'department_id' => 1,
            'program_duration_id' => 1,
        ])->create([
            'name' => 'National Diploma in peace and conflict studies',
            'program_abbreviation' => 'NDP',
            'fee' => 40500,
            'per_semester_fee' => 10125,
            'department_id' => 1,
            'program_duration_id' => 1,
        ])->create([
            'name' => 'Higher national Diploma in Peace and Conflict Studies',
            'program_abbreviation' => 'HDP',
            'fee' => 50500,
            'per_semester_fee' => 12625,
            'department_id' => 1,
            'program_duration_id' => 1,
        ]);
    }
}
