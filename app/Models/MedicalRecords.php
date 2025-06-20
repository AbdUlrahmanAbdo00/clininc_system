<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalRecords extends Model
{
    protected $fillable = [
        'appointment_id',
        'name',
        'date',
        'description'
    ];

    public function appointment() {
        return $this->belongsTo(Appointment::class);
    }

    public function imagingRecord() {
        return $this->hasOne(Imaging_Records::class);
    }

    public function diagnosticRecord() {
        return $this->hasOne(Diagnostic_Records::class);
    }
}
