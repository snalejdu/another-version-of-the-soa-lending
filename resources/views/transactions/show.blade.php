<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Transaction Details') }}
            </h2>
            <div>
                @if($transaction->type === 'payment')
                    <a href="{{ route('transactions.edit', $transaction) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">
                        Edit
                    </a>
                @endif
                <a href="{{ route('transactions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="text-lg font-semibold">Transaction Information</h3>
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            {{ $transaction->type === 'disbursement' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                            {{ ucfirst($transaction->type) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Transaction Number</p>
                            <p class="font-semibold text-lg">{{ $transaction->transaction_number }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Transaction Date</p>
                            <p class="font-semibold">{{ $transaction->transaction_date->format('F d, Y') }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Amount</p>
                            <p class="font-semibold text-2xl {{ $transaction->type === 'disbursement' ? 'text-red-600' : 'text-green-600' }}">
                                {{ $transaction->type === 'disbursement' ? '+' : '-' }}₱{{ number_format($transaction->amount, 2) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Balance After Transaction</p>
                            <p class="font-semibold text-lg">₱{{ number_format($transaction->balance_after, 2) }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Account</p>
                            <p class="font-semibold">
                                <a href="{{ route('accounts.show', $transaction->account) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    {{ $transaction->account->account_number }}
                                </a>
                            </p>
                        </div>

                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Customer</p>
                            <p class="font-semibold">
                                <a href="{{ route('customers.show', $transaction->account->customer) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    {{ $transaction->account->customer->name }}
                                </a>
                            </p>
                        </div>

                        @if($transaction->type === 'payment')
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Payment Method</p>
                                <p class="font-semibold">{{ $transaction->payment_method ? ucfirst($transaction->payment_method) : 'N/A' }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Reference Number</p>
                                <p class="font-semibold">{{ $transaction->reference_number ?? 'N/A' }}</p>
                            </div>
                        @endif

                        @if($transaction->processedBy)
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Processed By</p>
                                <p class="font-semibold">{{ $transaction->processedBy->name }}</p>
                            </div>
                        @endif

                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Created At</p>
                            <p class="font-semibold">{{ $transaction->created_at->format('M d, Y h:i A') }}</p>
                        </div>

                        @if($transaction->notes)
                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Notes</p>
                                <p class="font-semibold">{{ $transaction->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
