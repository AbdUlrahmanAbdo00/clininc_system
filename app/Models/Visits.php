<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visits extends Model
{
    protected $fillable = [
        'user_id', 'visit_date', 'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function diagnoses()
    {
        return $this->hasMany(Diagnoses::class);
    }

    public function testRequests()
    {
        return $this->hasMany(Test_requests::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referrals::class);
    }

    public function medicationSchedules()
    {
        return $this->hasMany(Medication_schedules::class);
    }

    public function symptomReports()
    {
        return $this->hasMany(Symptom_reports::class);
    }

    public function medicalConditions()
    {
        return $this->hasMany(Medical_conditions::class);
    }
}
