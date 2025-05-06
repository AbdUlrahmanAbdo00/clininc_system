<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disease_categories extends Model
{
    protected $fillable = ['name'];

    public function diseases()
    {
        return $this->hasMany(Diseases::class, 'category_id');
    }
}
