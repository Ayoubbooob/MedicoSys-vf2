<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class medical_file extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'dynamic_field','creation_date'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
