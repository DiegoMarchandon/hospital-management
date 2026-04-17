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
            'doctor_id' => Doctor::inRandomOrder()->first()->id,
            'patient_id' => Patient::inRandomOrder()->first()->id,
            'schedule_id' => Schedule::inRandomOrder()->first()->id,
            'appointment_date' => fake()->date(),
            'appointment_time' => fake()->time('H:i:s'),
            'reason' => fake()->sentence(),
            'status' => fake()->randomElement(['pending', 'confirmed', 'cancelled', 'completed']),
            'notes' => fake()->sentence(),
        ];
    }
}
