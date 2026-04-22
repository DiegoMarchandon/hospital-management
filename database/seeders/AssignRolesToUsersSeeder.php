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
        // Assign admin role
        $adminUser = User::where('email', 'admin@hospital.com')->first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }

        // Assign doctor role to all doctor users
        for ($i = 1; $i <= 10; $i++) {
            $user = User::where('email', "doctor_{$i}@hospital.com")->first();
            if ($user) {
                $user->assignRole('doctor');
            }
        }

        // Assign patient role to all patient users
        for ($i = 1; $i <= 20; $i++) {
            $user = User::where('email', "patient_{$i}@hospital.com")->first();
            if ($user) {
                $user->assignRole('patient');
            }
        }
    }
}
