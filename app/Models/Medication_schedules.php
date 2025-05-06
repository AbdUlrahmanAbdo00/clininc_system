<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medication_schedules extends Model
{
    protected $fillable = ['visit_id', 'medication_id', 'dosage'];

    public function visit()
    {
        return $this->belongsTo(Visits::class);
    }

    public function medication()
    {
        return $this->belongsTo(Medications::class);
    }
}
