<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referrals extends Model
{
    protected $fillable = ['visit_id', 'hospital_id'];

    public function visit()
    {
        return $this->belongsTo(Visits::class);
    }


}
