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
        return $this->hasMany(Consultation::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointement::class);
    }
}
