<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diseases extends Model
{
    protected $fillable = [
        'name'
    ];

    public function analytic() {
        return $this->belongsTo(Analytics::class);
    }
}
