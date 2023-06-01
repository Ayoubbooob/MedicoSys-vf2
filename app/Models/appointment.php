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
    public function patient()
    {
        return $this->belongsTo(patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(doctor::class);
    }

    public function major()
    {
        return $this->belongsTo(User::class, 'major_id');
    }
}
