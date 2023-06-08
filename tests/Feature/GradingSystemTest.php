<?php

namespace Tests\Feature;

use App\Models\GradingSystem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GradingSystemTest extends TestCase
{
    // use RefreshDatabase;

    public function testCreateGradingSystem()
    {
        $data = [
            'mark_from' => '70',
            'mark_to' => '100',
            'grade' => 'A',
            'interpretation' => 'Excellent',
        ];

        $user = User::find(2); // Replace 1 with the user ID you want to authenticate as

        $response = $this->actingAs($user)->json('POST', '/api/add-grading', $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'GradingSystem created successfully.',
            ]);

        // Additional assertions if required
    }


    public function testGradingSystemIndex()
    {
        // Retrieve existing grading systems from the database
        $gradingSystems = GradingSystem::all();

        $user = User::find(2); // Replace 2 with the user ID you want to authenticate as

        $response = $this->actingAs($user)->get('/api/view-gradings');

        // Assert the response status code
        $response->assertStatus(200);

        // Debug: Dump the response JSON
        dump($response->json());

        // Debug: Dump the expected grading systems

        // Assert that the response JSON contains the expected grading systems
        foreach ($gradingSystems as $gradingSystem) {
            $response->assertJsonFragment($gradingSystem->toArray());
        }
    }
}
