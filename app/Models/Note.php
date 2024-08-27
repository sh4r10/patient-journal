<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'content'];
    protected $dates = ['deleted_at'];
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    protected static function booted()
    {
        static::deleting(function ($note) {
            $userEmail = auth()->user()->email;
            $note->save();
        });
    }
}
