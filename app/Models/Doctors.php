<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctors extends Model
{
    protected $fillable = [
        'user_id',
        'specialization',
        'consultation_duration',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function visits()
    {
        return $this->hasMany(Visits::class, 'doctor_id');
    }

    public function testRequests()
    {
        return $this->hasMany(Test_requests::class, 'requested_by_doctor_id');
    }
    public function shifts()
    {
        return $this->belongsToMany(Shift::class,'doctor_shift', 'doctor_id', 'shift_id')->withTimestamps()->withPivot('days');
    }
}
