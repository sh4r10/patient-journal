<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class JournalEntry extends Model
{
    use HasFactory,SoftDeletes; //  SoftDeletes trait;

    protected $fillable = ['title', 'description', 'patient_id'];
    protected $dates = ['deleted_at'];


    protected static function booted() {
        static::deleting(function ($entry) {
            foreach ($entry->files as $file) {
                $file->delete();  // This soft deletes the file
            }
        });

        static::restoring(function ($entry) {
            $entry->files()->onlyTrashed()->restore();
        });
    }


    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
