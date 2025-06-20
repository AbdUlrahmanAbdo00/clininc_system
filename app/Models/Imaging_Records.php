<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imaging_Records extends Model
{
    protected $fillable = [
        'medical_image_id',
        'medical_record_id'
    ];

    public function medicalImage() {
        return $this->hasOne(MedicalImages::class);
    }

    public function medicalRecord() {
        return $this->hasOne(MedicalRecords::class);
    }
}
