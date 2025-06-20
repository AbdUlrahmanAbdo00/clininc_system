<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalImages extends Model
{
    protected $fillable = [
        'date',
        'path'
    ];

    public function imagingRecord() {
        return $this->belongsTo(Imaging_Records::class);
    }
}
