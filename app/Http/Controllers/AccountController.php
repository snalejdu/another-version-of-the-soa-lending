<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the accounts.
     */
    public function index()
    {
        $accounts = Account::with('customer')->latest()->paginate(20);
        return view('accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new account.
     */
    public function create()
    {
        $customers = Customer::all();
        return view('accounts.create', compact('customers'));
    }

    /**
     * Store a newly created account in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'account_number' => 'required|string|unique:accounts',
            'principal_amount' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'term_months' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // Calculate monthly payment and total amount
        $principal = $validated['principal_amount'];
        $rate = $validated['interest_rate'] / 100 / 12; // monthly rate
        $months = $validated['term_months'];
        
        // Calculate monthly payment using amortization formula
        if ($rate > 0) {
            $monthlyPayment = $principal * ($rate * pow(1 + $rate, $months)) / (pow(1 + $rate, $months) - 1);
        } else {
            $monthlyPayment = $principal / $months;
        }
        
        $totalAmount = $monthlyPayment * $months;
        
        // Calculate maturity date
        $startDate = new \DateTime($validated['start_date']);
        $maturityDate = (clone $startDate)->modify("+{$months} months");

        $account = Account::create([
            'customer_id' => $validated['customer_id'],
            'account_number' => $validated['account_number'],
            'principal_amount' => $principal,
            'interest_rate' => $validated['interest_rate'],
            'term_months' => $months,
            'monthly_payment' => round($monthlyPayment, 2),
            'total_amount' => round($totalAmount, 2),
            'balance' => round($totalAmount, 2),
            'start_date' => $validated['start_date'],
            'maturity_date' => $maturityDate->format('Y-m-d'),
            'status' => 'active',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('accounts.show', $account)
            ->with('success', 'Account created successfully.');
    }

    /**
     * Display the specified account.
     */
    public function show(Account $account)
    {
        $account->load('customer', 'transactions');
        return view('accounts.show', compact('account'));
    }

    /**
     * Show the form for editing the specified account.
     */
    public function edit(Account $account)
    {
        $customers = Customer::all();
        return view('accounts.edit', compact('account', 'customers'));
    }

    /**
     * Update the specified account in storage.
     */
    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,paid,overdue,defaulted',
            'notes' => 'nullable|string',
        ]);

        $account->update($validated);

        return redirect()->route('accounts.show', $account)
            ->with('success', 'Account updated successfully.');
    }

    /**
     * Remove the specified account from storage.
     */
    public function destroy(Account $account)
    {
        $account->delete();

        return redirect()->route('accounts.index')
            ->with('success', 'Account deleted successfully.');
    }
}
