<?php

namespace Tests\Feature\Api;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppointmentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_appointments()
    {
        Appointment::factory(15)->create();
        $response = $this->getJson('/api/appointments');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => ['id','doctor_id','patient_id','schedule_id','status']
            ]
        ]);
    }

    public function test_get_single_appointment()
    {
        $appointment = Appointment::factory()->create();
        $response = $this->getJson("/api/appointments/{$appointment->id}");

        $response ->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'id' => $appointment->id,
                'doctor_id' => $appointment->doctor_id,
                'patient_id' => $appointment->patient_id,
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
