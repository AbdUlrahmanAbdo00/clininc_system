<?php

namespace App\Models;
use App\Models\Appointment;
use App\Models\Analytics;
use App\Models\MedicalRecords;
use App\Models\MedicineSchedules;
use Illuminate\Database\Eloquent\Model;

class Patients extends Model
{
    protected $fillable = [
        'user_id',
     'daily_doses_number',
     'taken_doses',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
public function analytics()
{
    return $this->hasManyThrough(
        Analytics::class,     // الجدول النهائي
        Appointment::class,   // الجدول الوسيط
        'patient_id',         // العمود في appointments الذي يشير إلى patients
        'appointment_id',     // العمود في analytics الذي يشير إلى appointments
        'id',                 // المفتاح الأساسي في patients
        'id'                  // المفتاح الأساسي في appointments
    );
}

public function medicalRecord()
{
    return $this->hasManyThrough(
        MedicalRecords::class,    // الجدول النهائي
        Appointment::class,      // الجدول الوسيط
        'patient_id',            // العمود في appointments الذي يشير إلى patients
        'appointment_id',        // العمود في medical_records الذي يشير إلى appointments
        'id',                    // المفتاح الأساسي في patients
        'id'                     // المفتاح الأساسي في appointments
    );
}
public function medicineSchedule()
{
    return $this->hasManyThrough(
        MedicineSchedules::class,    // الجدول النهائي
        Appointment::class,         // الجدول الوسيط
        'patient_id',               // المفتاح الأجنبي في جدول appointments الذي يشير إلى patients
        'appointment_id',           // المفتاح الأجنبي في جدول medicine_schedules الذي يشير إلى appointments
        'id',                       // المفتاح الأساسي في جدول patients
        'id'                        // المفتاح الأساسي في جدول appointments
    );
}





}
