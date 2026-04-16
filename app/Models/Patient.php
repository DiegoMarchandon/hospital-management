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

    /**
     * Un paciente tiene varios turnos 
     */
    public function appointment(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
