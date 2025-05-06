<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medications extends Model
{
    protected $fillable = ['name'];

    public function medicationSchedules()
    {
        return $this->hasMany(Medication_schedules::class);
    }   
}
