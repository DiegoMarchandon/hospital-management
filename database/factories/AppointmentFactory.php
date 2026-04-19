<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'doctor_id' => Doctor::inRandomOrder()->first()->id,
            // 'patient_id' => Patient::inRandomOrder()->first()->id,
            // 'schedule_id' => Schedule::inRandomOrder()->first()->id,
            'doctor_id' => Doctor::factory()->create()->id,
            'patient_id' => Patient::factory()->create()->id,
            'schedule_id' => Schedule::factory()->create()->id,
            'appointment_date' => fake()->dateTimeBetween('now','+30 days')->format('Y-m-d'),
            'appointment_time' => fake()->time('H:i:s'),
            'reason' => fake()->sentence(),
            'status' => fake()->randomElement(['pending', 'confirmed', 'cancelled', 'completed']),
            'notes' => fake()->paragraph(),
        ];
    }
}
