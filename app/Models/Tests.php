<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tests extends Model
{
    protected $fillable = ['name'];

    public function testRequests()
    {
        return $this->hasMany(Test_requests::class);
    }
}
