<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['disbursement', 'payment']);
        $amount = fake()->randomFloat(2, 1000, 50000);
        $transactionDate = fake()->dateTimeBetween('-1 year', 'now');

        return [
            'account_id' => Account::factory(),
            'transaction_number' => 'TXN-' . fake()->unique()->numerify('##########'),
            'type' => $type,
            'amount' => $amount,
            'transaction_date' => $transactionDate->format('Y-m-d'),
            'balance_after' => fake()->randomFloat(2, 0, 500000),
            'payment_method' => $type === 'payment' ? fake()->randomElement(['cash', 'check', 'bank transfer', 'online']) : null,
            'reference_number' => $type === 'payment' ? fake()->optional()->numerify('REF-######') : null,
            'notes' => fake()->optional()->sentence(),
            'processed_by' => User::factory(),
        ];
    }

    /**
     * Create a disbursement transaction.
     */
    public function disbursement(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'disbursement',
            'payment_method' => null,
            'reference_number' => null,
        ]);
    }

    /**
     * Create a payment transaction.
     */
    public function payment(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'payment',
            'payment_method' => fake()->randomElement(['cash', 'check', 'bank transfer', 'online']),
            'reference_number' => fake()->numerify('REF-######'),
        ]);
    }
}
