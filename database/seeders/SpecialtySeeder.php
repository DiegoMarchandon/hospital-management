<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialties = [
            ['name' => 'Cardiología', 'description' => 'Enfermedades del corazón'],
            ['name' => 'Pediatría', 'description' => 'Medicina infantil'],
            ['name' => 'Dermatología', 'description' => 'Enfermedades de la piel'],
            ['name' => 'Ginecología', 'description' => 'Salud femenina'],
            ['name' => 'Neurología', 'description' => 'Enfermedades del sistema nervioso'],  
        ];

        foreach($specialties as $specialty){
            Specialty::create($specialty);
        }
    }
}
