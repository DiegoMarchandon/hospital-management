<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 doctors with matching User accounts
        for ($i = 1; $i <= 10; $i++) {
            $email = "doctor_{$i}@hospital.com";
            $name = "Dr. Doctor {$i}";
            
            // Create or find user
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => bcrypt('password'),
                ]
            );
            
            // Create doctor with same email
            Doctor::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'email' => $email,
                    'phone' => fake()->phoneNumber(),
                    'license_number' => fake()->unique()->regexify('[A-Z]{2}[0-9]{6}'),
                    'specialty_id' => ($i % 5) + 1, // Cycle through specialties 1-5
                    'bio' => fake()->sentence(),
                ]
            );
        }
    }
}
