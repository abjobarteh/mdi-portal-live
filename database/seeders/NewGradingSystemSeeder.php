<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GradingSystem;
class NewGradingSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        GradingSystem::create([
            'mark_from' => 90,
            'mark_to' => 100,
            'grade_point' => 4.0,
            'grade' => 'A+',
            'grade_type' => 'New',
            'interpretation' => 'Distinction'
        ]);

        GradingSystem::create([
            'mark_from' => 80,
            'mark_to' => 89,
            'grade_point' => 3.5,
            'grade' => 'A',
            'grade_type' => 'New',
            'interpretation' => 'Excellent'
        ]);

        GradingSystem::create([
            'mark_from' => 75,
            'mark_to' => 79,
            'grade_point' => 3.0,
            'grade' => 'B+',
            'grade_type' => 'New',
            'interpretation' => 'Very Good'
        ]);

        GradingSystem::create([
            'mark_from' => 70,
            'mark_to' => 74,
            'grade_point' => 2.5,
            'grade' => 'B',
            'grade_type' => 'New',
            'interpretation' => 'Good'
        ]);

        GradingSystem::create([
            'mark_from' => 65,
            'mark_to' => 69,
            'grade_point' => 2.0,
            'grade' => 'C+',
            'grade_type' => 'New',
            'interpretation' => 'Good'
        ]);

        GradingSystem::create([
            'mark_from' => 55,
            'mark_to' => 64,
            'grade_point' => 1.5,
            'grade' => 'C',
            'grade_type' => 'New',
            'interpretation' => 'Satisfactory'
        ]);

        GradingSystem::create([
            'mark_from' => 50,
            'mark_to' => 54,
            'grade_point' => 1.0,
            'grade' => 'D',
            'grade_type' => 'New',
            'interpretation' => 'Marginal'
        ]);

        GradingSystem::create([
            'mark_from' => 0,
            'mark_to' => 49,
            'grade_point' => 0.0,
            'grade' => 'F',
            'grade_type' => 'New',
            'interpretation' => 'Fail'
        ]);
    }
}
