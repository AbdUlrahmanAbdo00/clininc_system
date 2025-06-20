<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnostic_Records extends Model
{
    protected $fillable = [
        'medical_diagnostic_id',
        'medical_record_id'
    ];

    public function medicalDiagnostic() {
        return $this->hasOne(MedicalImages::class);
    }

    public function medicalRecord() {
        return $this->hasOne(MedicalRecords::class);
    }
}
