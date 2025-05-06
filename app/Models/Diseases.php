<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diseases extends Model
{
    protected $fillable = ['name', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Disease_categories::class, 'category_id');
    }

    public function diagnoses()
    {
        return $this->hasMany(Diagnoses::class);
    }
}
