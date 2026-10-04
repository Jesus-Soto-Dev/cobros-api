<?php

namespace Database\Factories;

use App\Enums\DebtStatus;
use App\Models\Debt;
use App\Models\Debtor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Debt>
 */
class DebtFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'debtor_id' => Debtor::factory(),
            'amount' => fake()->randomFloat(2, 100, 10000),
            'due_date' => fake()->dateTimeBetween('now', '+6 months'),
            'status' => fake()->randomElement(DebtStatus::cases()),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
