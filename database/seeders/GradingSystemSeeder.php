<?php

namespace Database\Seeders;

use App\Models\GradingSystem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradingSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GradingSystem::create([
            'mark_from' => 90,
            'mark_to' => 100,
            'grade_point' => 4.3,
            'grade' => 'A+',
            'interpretation' => 'Distinction'
        ])->create([
            'mark_from' => 80,
            'mark_to' => 89,
            'grade_point' => 4,
            'grade' => 'A',
            'interpretation' => 'Excellent'
        ])->create([
            'mark_from' => 70,
            'mark_to' => 79,
            'grade_point' => 3.7,
            'grade' => 'A-',
            'interpretation' => 'Very Good'
        ])->create([
            'mark_from' => 67,
            'mark_to' => 69,
            'grade_point' => 3.3,
            'grade' => 'B+',
            'interpretation' => 'Good'
        ])->create([
            'mark_from' => 64,
            'mark_to' => 66,
            'grade_point' => 3,
            'grade' => 'B',
            'interpretation' => 'Good'
        ])->create([
            'mark_from' => 60,
            'mark_to' => 63,
            'grade_point' => 2.7,
            'grade' => 'B-',
            'interpretation' => 'Above Average'
        ])->create([
            'mark_from' => 57,
            'mark_to' => 59,
            'grade_point' => 2.3,
            'grade' => 'C+',
            'interpretation' => 'Satisfactory'
        ])->create([
            'mark_from' => 54,
            'mark_to' => 56,
            'grade_point' => 2,
            'grade' => 'C',
            'interpretation' => 'Average'
        ])->create([
            'mark_from' => 50,
            'mark_to' => 53,
            'grade_point' => 1.7,
            'grade' => 'C-',
            'interpretation' => 'Marginal Pass'
        ])->create([
            'mark_from' => 40,
            'mark_to' => 49,
            'grade_point' => 1,
            'grade' => 'D',
            'interpretation' => 'Pass'
        ])->create([
            'mark_from' => 0,
            'mark_to' => 39,
            'grade_point' => 0,
            'grade' => 'F',
            'interpretation' => 'Fail'
        ]);
    }
}
