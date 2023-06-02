<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class patient extends Model implements Authenticatable

{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'cin',
        'password',
        'num',
        'gender',
        'email',
        'marital_status',
        'birth_date'
    ];

    public function getFullNameWithCinAttribute()
    {
        return $this->first_name . ' ' . $this->last_name . ' ' . $this->cin;
    }

    public function appointments()
    {
        return $this->hasMany(appointment::class);
    }

    public function appointmentRequests()
    {
        return $this->hasMany(AppointmentRequest::class);
    }



    public function imcs()
    {
        return $this->hasMany(imc::class);
    }

    protected $guard = 'patient';

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return null; // not used
    }

    public function setRememberToken($value)
    {
        // not used
    }

    public function getRememberTokenName()
    {
        return null; // not used
    }

    public function viaRemember()
    {
        return true;
    }

    public function getSessionTTL()
    {
        // Return the session lifetime for patients in minutes
        return config('session.patient_lifetime');
    }
//    public function getAuthIdentifierName()
//    {
//        return 'id';
//    }
//
//    public function getAuthIdentifier()
//    {
//        return $this->getKey();
//    }
//
//    public function getAuthPassword()
//    {
//        return $this->password;
//    }
//
//    public function getRememberToken()
//    {
//        return $this->remember_token;
//    }
//
//    public function setRememberToken($value)
//    {
//        $this->remember_token = $value;
//    }
//
//    public function getRememberTokenName()
//    {
//        return 'remember_token';
//    }
//
//    /**
//     * @return string[]
//     */
//    public function getFillable(): array
//    {
//        return $this->fillable;
//    }


}
