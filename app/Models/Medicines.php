<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicines extends Model
{
    protected $fillable = [
        'name'
    ];

public function medicineSchedules()
{
    return $this->hasMany(MedicineSchedules::class, 'medicine_id');
}

}
