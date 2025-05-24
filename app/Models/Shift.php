<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{ protected $fillable=[
    
    'start_time',
    'end_time',
    'shift_type',];
    public function doctor()
{
    return $this->belongsTo(Doctors::class, 'doctor_shift', 'shift_id', 'doctor_id')->withPivot('day');
}

}
