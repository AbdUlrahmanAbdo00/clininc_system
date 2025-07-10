<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineSchedules extends Model
{
    protected $fillable = [
        'appointment_id',
        'medicine_id',
        'quantity',
        'number_of_taken_doses',
        'rest_time',
        'last_time_has_taken',
        'description'
    ];

    public function appointment() {
        return $this->belongsTo(Appointment::class);
    }

    public function medicine() {
        return $this->hasOne(Medicines::class);
    }
}
