<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Symptom_reports extends Model
{
    protected $fillable = ['visit_id', 'symptom_id', 'description'];

    public function visit()
    {
        return $this->belongsTo(Visits::class);
    }

    public function symptom()
    {
        return $this->belongsTo(Symptoms::class);
    }
}
