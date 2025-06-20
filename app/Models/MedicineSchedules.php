<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineSchedules extends Model
{
    protected $fillable = [
        'appointment_id',
        'medicine_id',
        'total_number_of_doses',
        'number_of_taken_doses',
        'difference_time',
        'last_time_has_taken'
    ];

    public function appointment() {
        return $this->belongsTo(Appointment::class);
    }

    public function medicine() {
        return $this->hasOne(Medicines::class);
    }
}
