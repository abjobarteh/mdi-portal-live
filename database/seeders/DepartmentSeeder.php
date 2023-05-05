<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Department::create([
            'name' => 'DEPARTMENT OF DIPLOMACY & INTERNATIONAL RELATIONS',
        ])->create([
            'name' => 'DEPARTMENT OF GENDER & DEVELOPMENT',
        ])->create([
            'name' => 'DEPARTMENT OF BANKING & FINANCE',
        ])->create([
            'name' => 'DEPARTMENT OF INFORMATION AND COMMUNICATION TECHNOLOGY',
        ])->create([
            'name' => 'DEPARTMENT OF BUSINESS STUDIES',
        ])->create([
            'name' => 'DEPARTMENT OF GENERAL MANAGEMENT & POLICY ANALYSIS',
        ])->create([
            'name' => 'DEPARTMENT OF FINANCIAL MANAGEMENT',
        ]);
    }
}
