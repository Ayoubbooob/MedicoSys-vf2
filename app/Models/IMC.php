<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IMC extends Model
{
    use HasFactory;

    protected $fillable = [
        'weight',
        'size',
        'gender',
        'patient_id'
    ];

    public function patient()
    {
        return $this->belongsTo(patient::class);
    }}
