<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    // use SoftDeletes;

    protected $fillable = ['doctor_id', 'patient_id', 'date', 'start_date', 'end_date', 'finished', 'cancled','is_paid'];
        


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
        return $this->hasMany(Analytics::class);
    }

    public function medicalRecord() {
        return $this->hasMany(MedicalRecords::class);
    }

    public function medicineSchedule() {
        return $this->hasMany(MedicineSchedules::class);
    }
}
