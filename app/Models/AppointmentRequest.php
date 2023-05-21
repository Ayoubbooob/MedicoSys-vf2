<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentRequest extends Model
{
    protected $fillable = [
        'patient_id',
        'first_name',
        'last_name',
        'ppr',
        'cin',
        'num',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
