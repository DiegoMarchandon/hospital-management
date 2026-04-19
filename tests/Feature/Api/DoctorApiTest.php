<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Doctor;


class DoctorApiTest extends TestCase
{

    use RefreshDatabase;

    public function test_get_all_doctors()
    {
        // Arrange: Crear 5 doctores
        Doctor::factory(5)->create();

        // Act: Hacer GET request
        $response = $this->getJson('/api/doctors');

        // Assert: Verificar respuesta
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => ['id','name','email','specialty_id']
            ]
        ]);
        $response->assertJsonCount(5,'data');
    }

    public function test_get_single_doctor()
    {
        // Arrange
        $doctor = Doctor::factory()->create();

        // Act
        $response = $this->getJson("/api/doctors/{$doctor->id}");

        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'email' => $doctor->email
            ]
        ]);
    }

    public function test_get_doctor_includes_specialty()
    {
        $doctor = Doctor::factory()->create();
        $response = $this->getJson("/api/doctors/{$doctor->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('data.specialty.id',$doctor->specialty_id);
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
