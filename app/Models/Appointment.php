<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Appointment extends Model
{

    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'schedule_id',
        'appointment_date',
        'appointment_time',
        'reason',
        'status',
        'notes'
    ];


    /**
     * Una cita pertenece a un doctor
     */
    public function doctor():BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Una cita está dirigida a un paciente
     */
    public function patient():BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Una cita tiene un horario
     */
    public function schedule():BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function medicalRecords():HasMany
    {
        return $this->hasMany(MedicalRecord::class);
    }
}
