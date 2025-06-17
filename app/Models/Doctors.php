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
    
    public function shifts()
    {
        return $this->belongsToMany(Shift::class,'doctor_shift', 'doctor_id', 'shift_id')->withTimestamps()->withPivot('days');
    }
}
