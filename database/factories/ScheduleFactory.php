<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'doctor_id'=> Doctor::inRandomOrder()->first()->id,
            'day_of_week'=> fake()->randomElement(['Monday','Tuesday','Wednesday','Thursday','Friday']),
            'start_time'=> '09:00:00',
            'end_time'=>'17:00:00',
            'is_available' => fake()->boolean(80) //80% de probabilidad de ser disponible

        ];
    }
}
