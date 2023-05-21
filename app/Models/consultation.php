<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class consultation extends Model
{
    use HasFactory;
    protected $fillable = [
        'medicale_file_id',
        'doctor_id',
        'consultat_date',
        'report',
    ];

    public function medicalFile()
    {
        return $this->belongsTo(MedicalFile::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
