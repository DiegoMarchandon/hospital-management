<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reseteamos caché antes de crear permisos para evitar errores.
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Creamos los permisos
        Permission::firstOrCreate(['name' => 'view_appointments']);
        Permission::firstOrCreate(['name' => 'create_appointment']);
        Permission::firstOrCreate(['name' => 'cancel_appointment']);
        Permission::firstOrCreate(['name' => 'view_medical_records']);
        Permission::firstOrCreate(['name' => 'create_medical_record']);
        Permission::firstOrCreate(['name' => 'manage_schedule']);
        Permission::firstOrCreate(['name' => 'manage_users']);

        // Creamos los roles
        $patient = Role::firstOrCreate(['name' => 'patient']);
        $doctor = Role::firstOrCreate(['name' => 'doctor']);
        $admin = Role::firstOrCreate(['name' => 'admin']);

        // Asignamos permisos a los roles
        $patient->syncPermissions(['view_appointments','create_appointment','cancel_appointment']);
        $doctor->syncPermissions(['view_appointments','view_medical_records','create_medical_record']);
        $admin->syncPermissions(['view_appointments','manage_users','create_medical_record','manage_schedule']);
    }
}
