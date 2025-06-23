<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalDiagnostic extends Model
{
    protected $fillable = [
        'date',
        'content'
    ];

    public function diagnosticRecord() {
        return $this->belongsTo(Diagnostic_Records::class);
    }
}
