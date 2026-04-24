<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Tests\TestCase;

class ApiAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        \Spatie\Permission\Models\Role::create(['name' => 'doctor']);
        \Spatie\Permission\Models\Role::create(['name' => 'patient']);
    }

    public function test_api_returns_doctors_list()
    {
        Doctor::factory(5)->create();

        $response = $this->getJson('/api/doctors');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => ['id', 'name', 'email']
            ]
        ]);
    }

    public function test_api_returns_patients_list()
    {
        Patient::factory(5)->create();

        $response = $this->getJson('/api/patients');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => ['id', 'name', 'email']
            ]
        ]);
    }

    public function test_api_returns_appointments_filtered_by_user()
    {
        $user = User::factory()->create();
        $user->assignRole('patient');

        $patient = Patient::factory()->create([
            'email' => $user->email,
            'name' => $user->name
        ]);

        Appointment::factory(3)->create([
            'patient_id' => $patient->id
        ]);

        $response = $this->actingAs($user)->getJson('/api/appointments');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => ['id', 'doctor_id', 'patient_id', 'status']
            ]
        ]);
    }

    public function test_patient_can_create_appointment_via_api()
    {
        $user = User::factory()->create();
        $user->assignRole('patient');

        $patient = Patient::factory()->create([
            'email' => $user->email,
            'name' => $user->name
        ]);

        $doctor = Doctor::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/appointments', [
            'doctor_id' => $doctor->id,
            'appointment_date' => '2026-05-20',
            'appointment_time' => '15:00',
            'reason' => 'Consultation'
        ]);

        // Debug: Print response if error
        if ($response->status() !== 201) {
            echo "\nResponse: " . $response->content() . "\n";
        }

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'success',
            'data' => ['id', 'status', 'doctor_id', 'patient_id']
        ]);

        $this->assertDatabaseHas('appointments', [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'reason' => 'Consultation'
        ]);
    }

    public function test_api_returns_single_doctor()
    {
        $doctor = Doctor::factory()->create();

        $response = $this->getJson("/api/doctors/{$doctor->id}");

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

    public function test_api_returns_single_patient()
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

    public function test_doctor_can_update_appointment_status()
    {
        $user = User::factory()->create();
        $user->assignRole('doctor');

        $doctor = Doctor::factory()->create([
            'email' => $user->email,
            'name' => $user->name
        ]);

        $patient = Patient::factory()->create();
        $appointment = Appointment::factory()->create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($user)->putJson(
            "/api/appointments/{$appointment->id}",
            ['status' => 'confirmed']
        );

        $response->assertStatus(200);
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'confirmed'
        ]);
    }
}
