<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patients extends Model
{
    protected $fillable = [
        'user_id',
        'date_of_birth', 
        'MotherName', 
        'BirthDay',
        'NationalNumber',
        'Gender'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function visits()
    {
        return $this->hasMany(Visits::class, 'patient_id');
    }
}
