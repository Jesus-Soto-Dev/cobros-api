<?php

namespace Database\Factories;

use App\Models\Debtor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Debtor>
 */
class DebtorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'document' => strtoupper(fake()->unique()->numerify('########').fake()->randomLetter()),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('6########'),
        ];
    }
}
