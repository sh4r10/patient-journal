<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['name', 'path', 'journal_entry_id'];

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }
}
