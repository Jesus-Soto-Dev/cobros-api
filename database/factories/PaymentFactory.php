<?php

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Models\Debt;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
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
            'amount' => fake()->randomFloat(2, 50, 2000),
            'payment_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'method' => fake()->randomElement(PaymentMethod::cases()),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
