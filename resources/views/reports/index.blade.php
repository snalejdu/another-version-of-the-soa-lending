<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Monthly Transaction Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('reports.generate') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Year</label>
                                <select name="year" id="year" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">Select year</option>
                                    @foreach($years as $y)
                                        <option value="{{ $y }}" {{ (isset($year) && $year == $y) ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                                @error('year')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Month</label>
                                <select name="month" id="month" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">Select month</option>
                                    @foreach(range(1,12) as $m)
                                        <option value="{{ $m }}" {{ (isset($month) && $month == $m) ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                                    @endforeach
                                </select>
                                @error('month')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-2">
                            <button type="submit" name="action" value="filter" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Filter
                            </button>
                            <button type="submit" name="action" value="generate" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Generate PDF
                            </button>
                        </div>
                    </form>

                    @isset($transactions)
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold">
                                Transactions for {{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }}
                            </h3>



                            @if($transactions->isEmpty())
                                <p class="mt-2">No transactions found for the selected period.</p>
                            @else
                                <div class="overflow-x-auto mt-4">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Client Name</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Account No</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Disbursement</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Payment</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($transactions as $txn)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $txn->account->customer->name ?? '-' }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $txn->account->account_number ?? '-' }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                                        @if($txn->type === 'disbursement')
                                                            ₱{{ number_format($txn->amount,2) }}
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                                        @if($txn->type === 'payment')
                                                            ₱{{ number_format($txn->amount,2) }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            @endif
                        </div>

                    @endisset
                    @if(!empty($pdfName))
                        <div class="mt-4">
                            <p>PDF saved to <a href="{{ asset('pdf/reports/'.$pdfName) }}" target="_blank" class="text-blue-600 underline">{{ asset('pdf/reports/'.$pdfName) }}</a></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
