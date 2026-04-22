<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'date_of_birth',
        'id_number',
        'address',
        'city',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Un paciente tiene varios turnos 
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Un paciente tiene medicalRecords (uno por cada cita)
     */
        public function medicalRecords():HasMany
    {
        return $this->hasMany(MedicalRecord::class);
    }
}
