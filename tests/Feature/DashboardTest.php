<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        // Create roles
        \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        \Spatie\Permission\Models\Role::create(['name' => 'doctor']);
        \Spatie\Permission\Models\Role::create(['name' => 'patient']);
    }

    public function test_unauthenticated_user_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_admin_dashboard()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.admin');
    }

    public function test_doctor_can_access_doctor_dashboard()
    {
        $user = User::factory()->create([
            'email' => 'doctor@hospital.com'
        ]);
        $user->assignRole('doctor');
        
        // Create matching doctor profile
        Doctor::factory()->create([
            'email' => 'doctor@hospital.com',
            'name' => $user->name
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.doctor');
    }

    public function test_patient_can_access_patient_dashboard()
    {
        $user = User::factory()->create([
            'email' => 'patient@hospital.com'
        ]);
        $user->assignRole('patient');
        
        // Create matching patient profile
        Patient::factory()->create([
            'email' => 'patient@hospital.com',
            'name' => $user->name
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.patient');
    }

    public function test_doctor_dashboard_returns_correct_structure()
    {
        $user = User::factory()->create([
            'email' => 'doctor@hospital.com'
        ]);
        $user->assignRole('doctor');
        
        Doctor::factory()->create([
            'email' => 'doctor@hospital.com',
            'name' => $user->name
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertViewHasAll(['doctor', 'upcoming_appointments', 'stats']);
    }

    public function test_patient_dashboard_returns_correct_structure()
    {
        $user = User::factory()->create([
            'email' => 'patient@hospital.com'
        ]);
        $user->assignRole('patient');
        
        Patient::factory()->create([
            'email' => 'patient@hospital.com',
            'name' => $user->name
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertViewHasAll(['patient', 'appointments', 'medical_records', 'stats']);
    }
}
