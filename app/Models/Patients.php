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

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
public function analytics()
{
    return $this->hasManyThrough(
        Analytics::class,     
        Appointment::class,   
        'patient_id',         
        'appointment_id',     
        'id',                
        'id'                  
    );
}

public function medicalRecord()
{
    return $this->hasManyThrough(
        MedicalRecords::class,    
        Appointment::class,      
        'patient_id',            
        'appointment_id',        
        'id',                    
        'id'                     
    );
}
public function medicineSchedule()
{
    return $this->hasManyThrough(
        MedicineSchedules::class,
        Appointment::class,         
        'patient_id',               
        'appointment_id',           
        'id',
        'id'
    );
}





}
