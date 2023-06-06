<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class appointment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'informations_supplementaires' => 'json',
    ];
    // public function medical_file()
    // {
    //     return $this->belongsTo(medical_file::class);
    // }
    public function medicalFile()
    {
        return $this->belongsTo(medical_file::class, 'medical_file_id');
    }


    public function doctor()
    {
        return $this->belongsTo(doctor::class);
    }
}
