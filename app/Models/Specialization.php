<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    protected $fillable =[
        'name',
        'path'
    ];
   public function doctors()
{
    return $this->hasMany(Doctors::class);
}

}
