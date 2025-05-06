<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medical_conditions extends Model
{
    protected $fillable = ['visit_id', 'description'];

    public function visit()
    {
        return $this->belongsTo(Visits::class);
    }
}
