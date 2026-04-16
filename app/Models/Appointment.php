<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
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
     * 
     */
    public function schedule():BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }
}
