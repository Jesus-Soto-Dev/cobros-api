<?php

namespace Database\Factories;

use App\Models\DebtAction;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\DebtActionType;
use App\Models\Debt;
use App\Models\User;

/**
 * @extends Factory<DebtAction>
 */
class DebtActionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'debt_id' => Debt::factory(),
            'user_id' => User::factory(),
            'type' => fake()->randomElement(DebtActionType::cases()),
            'notes' => fake()->optional()->sentence(),
            'action_date' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
