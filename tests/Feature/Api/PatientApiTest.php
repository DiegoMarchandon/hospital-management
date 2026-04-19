<?php

namespace Tests\Feature\Api;

use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PatientApiTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_get_all_patients()
    {
        Patient::factory(10)->create();
        $response = $this->getJson('/api/patients');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => ['id','name','email','phone']
            ]
        ]);
        $response->assertJsonCount(10,'data');
    }

    public function test_get_single_patient()
    {
        $patient = Patient::factory()->create();
        $response = $this->getJson("/api/patients/{$patient->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'id' => $patient->id,
                'name' => $patient->name,
                'email' => $patient->email
            ]
        ]);
    }
    
    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }
}
