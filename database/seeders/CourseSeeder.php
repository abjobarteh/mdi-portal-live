<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Course::create([
            'course_code' => 'BFN101',
            'course_name' => 'Introduction to National Diploma in Banking & Finance',
            'program_id' => 1
        ])->create([
            'course_code' => 'BFN102',
            'course_name' => 'Accounting for Banking and Finance',
            'program_id' => 1
        ])->create([
            'course_code' => 'BFN103',
            'course_name' => 'Business Communication',
            'program_id' => 1
        ])->create([
            'course_code' => 'BFN201',
            'course_name' => 'Money and Capital Markets',
            'program_id' => 2
        ])->create([
            'course_code' => 'BFN202',
            'course_name' => 'Financial Management',
            'program_id' => 2
        ]);
    }
}
