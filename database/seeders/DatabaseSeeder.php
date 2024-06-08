<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use App\Models\Journal;
use App\Models\JournalEntry;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'username' => 'Doctor'
        // ]);
        //
        // Patient::factory(1)->create();
        JournalEntry::factory(15)->create();
        $this->call(TreatmentSeeder::class);
    }
}
