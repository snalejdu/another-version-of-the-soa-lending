<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = Account::all();

        if ($accounts->isEmpty()) {
            $this->command->warn('No accounts found. Please run AccountSeeder first.');
            return;
        }

        // Get employee users (those without customer accounts)
        $employees = User::doesntHave('customer')->get();
        if ($employees->isEmpty()) {
            $employees = User::take(3)->get();
        }

        foreach ($accounts as $account) {
            $employee = $employees->random();
            
            // Create initial disbursement transaction
            Transaction::create([
                'account_id' => $account->id,
                'transaction_number' => 'TXN-' . str_pad($account->id * 1000, 10, '0', STR_PAD_LEFT),
                'type' => 'disbursement',
                'amount' => $account->principal_amount,
                'transaction_date' => $account->start_date,
                'balance_after' => $account->total_amount,
                'payment_method' => null,
                'reference_number' => null,
                'notes' => 'Initial loan disbursement',
                'processed_by' => $employee->id,
            ]);

            // Create payment transactions for accounts that have been running
            if ($account->status === 'paid') {
                // Create payments that paid off the loan
                $remainingBalance = $account->total_amount;
                $paymentCount = rand(6, 12);
                
                for ($i = 1; $i <= $paymentCount; $i++) {
                    $paymentAmount = $i < $paymentCount 
                        ? $account->monthly_payment 
                        : $remainingBalance; // Last payment clears remaining balance
                    
                    $remainingBalance -= $paymentAmount;
                    $transactionDate = (new \DateTime($account->start_date))
                        ->modify("+{$i} months");
                    
                    Transaction::create([
                        'account_id' => $account->id,
                        'transaction_number' => 'TXN-' . str_pad(($account->id * 1000) + $i, 10, '0', STR_PAD_LEFT),
                        'type' => 'payment',
                        'amount' => round($paymentAmount, 2),
                        'transaction_date' => $transactionDate->format('Y-m-d'),
                        'balance_after' => max(0, round($remainingBalance, 2)),
                        'payment_method' => fake()->randomElement(['cash', 'check', 'bank transfer', 'online']),
                        'reference_number' => fake()->numerify('REF-######'),
                        'notes' => $i === $paymentCount ? 'Final payment - account settled' : null,
                        'processed_by' => $employees->random()->id,
                    ]);
                }
            } elseif ($account->status === 'active' || $account->status === 'overdue') {
                // Create some payment history
                $monthsElapsed = max(0, floor((time() - strtotime($account->start_date)) / (30 * 24 * 60 * 60)));
                $paymentsMade = min($monthsElapsed, rand(1, $account->term_months - 1));
                
                $remainingBalance = $account->total_amount;
                
                for ($i = 1; $i <= $paymentsMade; $i++) {
                    $paymentAmount = $account->monthly_payment;
                    $remainingBalance -= $paymentAmount;
                    
                    $transactionDate = (new \DateTime($account->start_date))
                        ->modify("+{$i} months");
                    
                    Transaction::create([
                        'account_id' => $account->id,
                        'transaction_number' => 'TXN-' . str_pad(($account->id * 1000) + $i, 10, '0', STR_PAD_LEFT),
                        'type' => 'payment',
                        'amount' => round($paymentAmount, 2),
                        'transaction_date' => $transactionDate->format('Y-m-d'),
                        'balance_after' => round($remainingBalance, 2),
                        'payment_method' => fake()->randomElement(['cash', 'check', 'bank transfer', 'online']),
                        'reference_number' => fake()->numerify('REF-######'),
                        'notes' => null,
                        'processed_by' => $employees->random()->id,
                    ]);
                }
                
                // Update the account's current balance
                $account->balance = round($remainingBalance, 2);
                $account->save();
            }
        }
    }
}
