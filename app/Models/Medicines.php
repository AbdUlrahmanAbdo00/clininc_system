<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicines extends Model
{
    protected $fillable = [
        'name'
    ];

    public function medicineSchedule() {
        return $this->belongsTo(MedicineSchedules::class);
    }
}
