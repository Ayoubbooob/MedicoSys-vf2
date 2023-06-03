<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class medical_file extends Model
{
    use HasFactory;

    //    protected $fillable = ['patient_id', 'dynamic_field','creation_date'];

    //    protected $fillable = ['ppr', 'patient_id',  'antecedents', 'biometrie', 'traitement_chronique', 'vaccination', 'examen_biologiques'];


    protected $guarded = [];


    protected $casts = [
        'dynamic_fields' => 'array',
    ];
    //    protected $casts = [
    //        'antecedents' => 'array',
    //        'biometrie' => 'array',
    //        'vaccination' => 'array',
    //        'examen_biologiques' => 'array',
    //    ];
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }


    //Accessor to decode and encode json attribute
    public function getAntecedentsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setAntecedentsAttribute($value)
    {
        $this->attributes['antecedents'] = json_encode($value);
    }

    public function getBiometrieAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setBiometrieAttribute($value)
    {
        $this->attributes['biometrie'] = json_encode($value);
    }

    public function getTraitementChroniqueAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setTraitementChroniqueAttribute($value)
    {
        $this->attributes['traitement_chronique'] = json_encode($value);
    }

    public function getVaccinationAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setVaccinationAttribute($value)
    {
        $this->attributes['vaccination'] = json_encode($value);
    }

    public function getExamenBiologiquesAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setExamenBiologiquesAttribute($value)
    {
        $this->attributes['examen_biologiques'] = json_encode($value);
    }
}
