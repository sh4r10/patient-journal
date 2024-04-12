<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'path', 'mime', 'journal_entry_id'];

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id', 'id');
    }
}
