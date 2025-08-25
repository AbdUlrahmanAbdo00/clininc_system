<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    // use SoftDeletes;

    protected $fillable = ['doctor_id', 'patient_id', 'date', 'start_date', 'end_date', 'finished', 'cancled'];
        


    public function user()

    {
        return $this->belongsTo(User::class);
    }

    public function patient()
{
    return $this->belongsTo(Patients::class);
}
    public function doctor(){
        return $this->belongsTo(Doctors::class);
    }

    public function analytics() {
        $this->hasMany(Analytics::class);
    }

    public function medicalRecord() {
        $this->hasMany(MedicalRecords::class);
    }

    public function medicineSchedule() {
        $this->hasMany(MedicineSchedules::class);
    }

    // Accessors للحقول
    public function getCompletedAttribute($value)
    {
        return $this->finished == 1 || $this->finished == true;
    }

    public function getCanceledAttribute($value)
    {
        return $this->cancled == 1 || $this->cancled == true;
    }
}
