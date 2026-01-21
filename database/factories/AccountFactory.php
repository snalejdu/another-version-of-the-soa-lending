<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $principalAmount = fake()->randomFloat(2, 10000, 500000);
        $interestRate = fake()->randomFloat(2, 3, 15); // 3% to 15%
        $termMonths = fake()->randomElement([6, 12, 18, 24, 36, 48, 60]);
        
        // Calculate monthly payment using amortization formula
        $rate = $interestRate / 100 / 12; // monthly rate
        if ($rate > 0) {
            $monthlyPayment = $principalAmount * ($rate * pow(1 + $rate, $termMonths)) / (pow(1 + $rate, $termMonths) - 1);
        } else {
            $monthlyPayment = $principalAmount / $termMonths;
        }
        
        $totalAmount = $monthlyPayment * $termMonths;
        $startDate = fake()->dateTimeBetween('-2 years', 'now');
        $maturityDate = (clone $startDate)->modify("+{$termMonths} months");
        
        // Determine balance and status based on start date
        $monthsElapsed = max(0, floor((time() - $startDate->getTimestamp()) / (30 * 24 * 60 * 60)));
        $paymentsMade = min($monthsElapsed, $termMonths);
        $balance = max(0, $totalAmount - ($monthlyPayment * $paymentsMade));
        
        $status = 'active';
        if ($balance <= 0) {
            $status = 'paid';
        } elseif ($maturityDate < new \DateTime()) {
            $status = fake()->randomElement(['overdue', 'defaulted', 'active']);
        }

        return [
            'customer_id' => Customer::factory(),
            'account_number' => 'ACC-' . fake()->unique()->numerify('######'),
            'principal_amount' => $principalAmount,
            'interest_rate' => $interestRate,
            'term_months' => $termMonths,
            'monthly_payment' => round($monthlyPayment, 2),
            'total_amount' => round($totalAmount, 2),
            'balance' => round($balance, 2),
            'start_date' => $startDate->format('Y-m-d'),
            'maturity_date' => $maturityDate->format('Y-m-d'),
            'status' => $status,
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Create an active account.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Create a paid account.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'balance' => 0,
        ]);
    }

    /**
     * Create an overdue account.
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'overdue',
        ]);
    }
}
