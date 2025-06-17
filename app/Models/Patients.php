<?php

namespace App\Models;

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


}
