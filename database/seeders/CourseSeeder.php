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
            'course_code' => 'ACC 211',
            'course_name' => 'Financial Accounting',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 213',
            'course_name' => 'Organizational Behaviour',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 212',
            'course_name' => 'Managerial Communication',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 214',
            'course_name' => 'Taxation 1',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 215',
            'course_name' => 'Auditing 1',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 221',
            'course_name' => 'Intermediate Financial Accounting I',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 222',
            'course_name' => 'Cost Accounting',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 223',
            'course_name' => 'Management Accounting I',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 224',
            'course_name' => 'Taxation II',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 225',
            'course_name' => 'Auditing II',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 311',
            'course_name' => 'International Finance',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 312',
            'course_name' => 'Professional Ethics',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 313',
            'course_name' => 'Accounting Theory and Practice',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 314',
            'course_name' => 'Intermediate Financial Accounting II',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 315',
            'course_name' => 'Management Accounting II',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 321',
            'course_name' => 'Strategic Managementt',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 322',
            'course_name' => 'International Finance',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 323',
            'course_name' => 'Investment Analysis',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 324',
            'course_name' => 'Accounting Information System',
            'program_id' => 1
        ])->create([
            'course_code' => 'ACC 325',
            'course_name' => 'Project Writing I for Accountancy',
            'program_id' => 1
        ])->create([
            'course_code' => 'CPS 121',
            'course_name' => 'Introduction to Computer Science',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 122',
            'course_name' => 'IT Hardware & Systems',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 123',
            'course_name' => 'Computer Security',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 124',
            'course_name' => 'Programming Logic and Design',
            'program_id' => 5
        ])->create([
            'course_code' => 'ENG 100',
            'course_name' => 'English 2 (Academic Writing)',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 211',
            'course_name' => 'Networking I',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 212',
            'course_name' => 'Database I',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 213',
            'course_name' => 'Web programming I',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 214',
            'course_name' => 'Computer programming I',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 215',
            'course_name' => 'Operating System',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 221',
            'course_name' => 'Networking II',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 222',
            'course_name' => 'Database II',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 223',
            'course_name' => 'Web programming II',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 224',
            'course_name' => 'Computer programming II',
            'program_id' => 5
        ])->create([
            'course_code' => 'CPS 225',
            'course_name' => 'Junior Project Writing I',
            'program_id' => 5
        ]);
    }
}
