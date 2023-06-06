<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class doctor extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function consultations()
    {
        return $this->hasMany(consultation::class);
    }

    public function appointments()
    {
        return $this->hasMany(appointment::class);
    }
    public function medical_file()
    {
        return $this->hasMany(medical_file::class);
    }
}
