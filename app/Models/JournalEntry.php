<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'patient_id'];

    public function patient() {
        return $this->belongsTo(Patient::class);
    }

    public function files() {
        return $this->hasMany(File::class);
    }
}
