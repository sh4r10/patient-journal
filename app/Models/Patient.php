<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
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

        protected static function booted()
        {
            static::deleting(function ($patient) {
                $userEmail = auth()->user()->email;
                foreach ($patient->journalEntries as $entry) {
                    $entry->deleted_by = $userEmail; // Set the deleted_by field
                    $entry->save();
                    $entry->delete(); // Soft delete the entry
                }
                foreach ($patient->notes as $note) {
                    $note->deleted_by = $userEmail; // Set the deleted_by field
                    $note->save();
                    $note->delete(); // Soft delete the note
                }
                $patient->deleted_by = $userEmail; // Set the deleted_by field for the patient
                $patient->save();
            });
    
            static::restoring(function ($patient) {
                $patient->journalEntries()->onlyTrashed()->restore();
                $patient->notes()->onlyTrashed()->restore();
            });
        }

}
