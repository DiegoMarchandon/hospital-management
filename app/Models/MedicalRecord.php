<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalRecord extends Model
{
    protected $fillable = [
        'doctor_id',
        'appointment_id',
        'patient_id',
        'comments',
        'prescription',
        'treatment',
        'lab_tests',
        'diagnosis'
    ];

    /**
     * un historial médico puede estar relacionado con varios doctores
     */
    public function doctors():HasMany
    {
        return $this->hasMany(Doctor::class);
    }

    /**
     * un historial médico está compuesto por varias citas
     */
    public function appointments():HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * un historial médico está compuesto por varias citas
     */
    public function patient():BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Un paciente tiene muchos registros médicos
     */
    public function medicalRecords():HasMany
    {
        return $this->hasMany(MedicalRecord::class);
    }
}
