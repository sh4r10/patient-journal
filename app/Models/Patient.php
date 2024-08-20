<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Patient extends Model
{
    use HasFactory ,SoftDeletes; //  SoftDeletes trait
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name', 'personnummer', 'email', 'phone'
    ];

   
        
    
        public function journalEntries() {
            return $this->hasMany(JournalEntry::class);
        }
    
        public function treatments()
        {
            return $this->belongsToMany(Treatment::class);
        }
        

        public function notes()
        {
            return $this->hasMany(Note::class);
        }

}
