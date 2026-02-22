<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendTransactionEmailJob;

class TransactionController extends Controller
{
    /**
     * Display a listing of the transactions.
     */
    public function index()
    {
        $transactions = Transaction::with('account.customer')
            ->latest()
            ->paginate(20);

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new transaction.
     */
    public function create()
    {
        $accounts = Account::with('customer')
            ->where('status', 'active')
            ->get();

        return view('transactions.create', compact('accounts'));
    }

    /**
     * Store a newly created transaction in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'type' => 'required|in:disbursement,payment',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'payment_method' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $account = Account::findOrFail($validated['account_id']);

            // Calculate new balance
            $newBalance = $validated['type'] === 'disbursement'
                ? $account->balance + $validated['amount']
                : $account->balance - $validated['amount'];

            // Generate unique transaction number
            $transactionNumber = 'TXN-' . now()->format('YmdHis') . '-' . rand(1000, 9999);

            // Create transaction
            $transaction = Transaction::create([
                'account_id' => $account->id,
                'transaction_number' => $transactionNumber,
                'type' => $validated['type'],
                'amount' => $validated['amount'],
                'transaction_date' => $validated['transaction_date'],
                'balance_after' => $newBalance,
                'payment_method' => $validated['payment_method'] ?? null,
                'reference_number' => $validated['reference_number'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'processed_by' => Auth::id(),
            ]);

            // Update account balance and status
            $account->balance = $newBalance;
            if ($newBalance <= 0) {
                $account->status = 'paid';
            } elseif ($newBalance > 0 && $account->status === 'paid') {
                $account->status = 'active';
            }
            $account->save();

            // Dispatch queued email
            SendTransactionEmailJob::dispatch($transaction->id);
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction created successfully.');
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load('account.customer', 'processedBy');

        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified transaction.
     */
    public function edit(Transaction $transaction)
    {
        $accounts = Account::with('customer')->get();

        return view('transactions.edit', compact('transaction', 'accounts'));
    }

    /**
     * Update the specified transaction in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'payment_method' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified transaction from storage.
     */
    public function destroy(Transaction $transaction)
    {
        DB::transaction(function () use ($transaction) {
            $account = $transaction->account;

            // Reverse the transaction effect on account balance
            if ($transaction->type === 'disbursement') {
                $account->balance -= $transaction->amount;
            } else {
                $account->balance += $transaction->amount;
            }

            // Update account status
            if ($account->balance > 0 && $account->status === 'paid') {
                $account->status = 'active';
            }

            $account->save();

            // Delete the transaction
            $transaction->delete();
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }
}
