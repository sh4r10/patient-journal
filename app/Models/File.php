<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
class File extends Model
{
    use HasFactory ,SoftDeletes; //  SoftDeletes trait;
    protected $fillable = ['id', 'path', 'mime', 'journal_entry_id'];
    protected $dates = ['deleted_at'];
    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id', 'id');
    }


    protected static function booted()
    {
        static::deleting(function ($file) {
            $userEmail = auth()->user()->email;
            $file->save();
        });
    }
}
