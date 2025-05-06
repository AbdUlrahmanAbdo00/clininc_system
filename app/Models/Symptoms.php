<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Symptoms extends Model
{
    protected $fillable = ['name'];

    public function symptomReports()
    {
        return $this->hasMany(Symptom_reports::class);
    }
}
