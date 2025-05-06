<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test_requests extends Model
{
    protected $fillable = ['visit_id', 'test_id', 'requested_by_doctor_id'];

    public function visit()
    {
        return $this->belongsTo(Visits::class);
    }

    public function test()
    {
        return $this->belongsTo(Tests::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'requested_by_doctor_id');
    }
}
