<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'content'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
