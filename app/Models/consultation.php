<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class consultation extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'rapport_du_consultation' => 'json',
    ];
    public function medical_file()
    {
        return $this->belongsTo(medical_file::class);
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
