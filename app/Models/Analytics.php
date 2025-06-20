<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analytics extends Model
{
    protected $fillable = [
        'appointment_id',
        'name',
        'type',
        'date',
        'description',
        'disease_id'
    ];

    public function appointment() {
        return $this->belongsTo(Appointment::class);
    }

    public function disease() {
        return $this->hasOne(Diseases::class);
    }
}
