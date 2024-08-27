<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Treatment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $dates = ['deleted_at'];


    public function patients()
    {
        return $this->belongsToMany(Patient::class);
    }

    protected static function booted()
    {
        static::deleting(function ($treatment) {
            $userEmail = auth()->user()->email;
            $treatment->save();
        });
    }
}
