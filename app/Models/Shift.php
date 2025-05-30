<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [

        'start_time',
        'end_time',
        'shift_type',
        'start_break_time',
        'end_break_time',
    ];
    public function doctor()
    {
        return $this->belongsToMany(Doctors::class, 'doctor_shift', 'shift_id', 'doctor_id')->withPivot('days');
    }
}
    