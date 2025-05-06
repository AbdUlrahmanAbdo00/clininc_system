<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnoses extends Model
{
    protected $fillable = ['visit_id', 'disease_id'];

    public function visit()
    {
        return $this->belongsTo(Visits::class);
    }

    public function disease()
    {
        return $this->belongsTo(Diseases::class);
    }
}
