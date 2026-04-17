<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class AssignRolesToUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asignar admin al Test User
        $testUser = User::where('email','text@example.com')->first();
        if($testUser){
            $testUser->assignRole('admin');
        }

        // Asignar role 'doctor' a cada Doctor

        Doctor::all()->each(function($doctor){
            $user = User::create([
                'name' => $doctor->name,
                'email' => 'doctor_'.$doctor->id .'@hospital.com',
                'password' => bcrypt('password'),
            ]);
            $user->assignRole('doctor');
        });

        // Asignar role 'patient' a cada Patient
        Patient::all()->each(function($patient){
            $user = User::create([
                'name' => $patient->name,
                'email' => 'patient_'.$patient->id.'@hospital.com',
                'password' => bcrypt('password'),
            ]);
            $user->assignRole('patient');
        });

    }
}
