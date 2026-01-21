<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Account Details') }}
            </h2>
            <div>
                <a href="{{ route('accounts.edit', $account) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Edit
                </a>
                <a href="{{ route('accounts.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Account Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-semibold">Account Information</h3>
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            @if($account->status === 'active') bg-blue-100 text-blue-800
                            @elseif($account->status === 'paid') bg-green-100 text-green-800
                            @elseif($account->status === 'overdue') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($account->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Account Number</p>
                            <p class="font-semibold text-lg">{{ $account->account_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Customer</p>
                            <p class="font-semibold">
                                <a href="{{ route('customers.show', $account->customer) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    {{ $account->customer->name }}
                                </a>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Interest Rate</p>
                            <p class="font-semibold">{{ $account->interest_rate }}%</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Principal Amount</p>
                            <p class="font-semibold text-lg">₱{{ number_format($account->principal_amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Amount</p>
                            <p class="font-semibold text-lg">₱{{ number_format($account->total_amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Current Balance</p>
                            <p class="font-semibold text-lg text-red-600 dark:text-red-400">₱{{ number_format($account->balance, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Monthly Payment</p>
                            <p class="font-semibold">₱{{ number_format($account->monthly_payment, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Term</p>
                            <p class="font-semibold">{{ $account->term_months }} months</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Progress</p>
                            <p class="font-semibold">{{ number_format((($account->total_amount - $account->balance) / $account->total_amount) * 100, 1) }}% paid</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Start Date</p>
                            <p class="font-semibold">{{ $account->start_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Maturity Date</p>
                            <p class="font-semibold">{{ $account->maturity_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Created</p>
                            <p class="font-semibold">{{ $account->created_at->format('M d, Y') }}</p>
                        </div>
                        @if($account->notes)
                            <div class="md:col-span-3">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Notes</p>
                                <p class="font-semibold">{{ $account->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Transactions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Transactions</h3>
                        <a href="{{ route('transactions.create', ['account_id' => $account->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Add Transaction
                        </a>
                    </div>

                    @if($account->transactions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Transaction #</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Balance After</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($account->transactions->sortByDesc('transaction_date') as $transaction)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transaction->transaction_date->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transaction->transaction_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $transaction->type === 'disbursement' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ ucfirst($transaction->type) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $transaction->type === 'disbursement' ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $transaction->type === 'disbursement' ? '+' : '-' }}₱{{ number_format($transaction->amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">₱{{ number_format($transaction->balance_after, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('transactions.show', $transaction) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No transactions found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
