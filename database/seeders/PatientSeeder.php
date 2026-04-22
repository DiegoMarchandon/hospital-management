<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 20 patients with matching User accounts
        for ($i = 1; $i <= 20; $i++) {
            $email = "patient_{$i}@hospital.com";
            $name = "Patient {$i}";
            
            // Create or find user
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => bcrypt('password'),
                ]
            );
            
            // Create patient with same email
            Patient::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'email' => $email,
                    'phone' => fake()->phoneNumber(),
                    'date_of_birth' => fake()->dateTimeBetween('-70 years', '-18 years'),
                    'address' => fake()->streetAddress(),
                    'city' => fake()->city(),
                    'id_number' => fake()->unique()->regexify('[0-9]{8}[A-Z]'),
                ]
            );
        }
    }
}
