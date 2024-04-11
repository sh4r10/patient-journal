<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Patient; // Make sure this uses the correct namespace

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JournalEntry>
 */
class JournalEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->realTextBetween(10, 40),
            'description' => fake()->realTextBetween(100, 600),
            // Assuming you have a Patient model and corresponding patients table
            'patient_id' => Patient::query()->inRandomOrder()->first()->id ?? Patient::factory(),
        ];
    }
}
