<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Mail\TransactionCreatedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendTransactionEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $transactionId;

    // Retry up to 3 times if there is a transient failure
    public int $tries = 3;

    // Wait 10 seconds before retrying
    public int $retryAfter = 10;

    /**
     * Create a new job instance.
     */
    public function __construct(int $transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $transaction = Transaction::with('account.customer')
            ->find($this->transactionId);

        if (!$transaction) {
            Log::warning('Transaction email skipped: transaction not found', [
                'transaction_id' => $this->transactionId,
            ]);
            return;
        }

        $customer = $transaction->account?->customer;

        if (!$customer) {
            Log::warning('Transaction email skipped: account has no customer', [
                'transaction_id' => $transaction->id,
                'account_id' => $transaction->account_id,
            ]);
            return;
        }

        if (empty($customer->email)) {
            Log::warning('Transaction email skipped: customer email missing', [
                'transaction_id' => $transaction->id,
                'customer_id' => $customer->id,
            ]);
            return;
        }

        try {
            Mail::to($customer->email)
                ->queue(new TransactionCreatedMail($transaction));

            Log::info('Transaction email queued successfully', [
                'transaction_id' => $transaction->id,
                'customer_email' => $customer->email,
            ]);
        } catch (\Throwable $e) {
            // Log the error and allow retrying
            Log::error('Failed to queue transaction email', [
                'transaction_id' => $transaction->id,
                'customer_email' => $customer->email,
                'error' => $e->getMessage(),
            ]);

            // Rethrow to let Laravel retry if needed
            throw $e;
        }
    }
}
