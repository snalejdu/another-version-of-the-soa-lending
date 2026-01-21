<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Account Status') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('accounts.update', $account) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
                            <h4 class="font-semibold mb-2">Account Information</h4>
                            <p><strong>Account #:</strong> {{ $account->account_number }}</p>
                            <p><strong>Customer:</strong> {{ $account->customer->name }}</p>
                            <p><strong>Principal:</strong> ₱{{ number_format($account->principal_amount, 2) }}</p>
                            <p><strong>Balance:</strong> ₱{{ number_format($account->balance, 2) }}</p>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <select name="status" id="status" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="active" {{ old('status', $account->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="paid" {{ old('status', $account->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="overdue" {{ old('status', $account->status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="defaulted" {{ old('status', $account->status) == 'defaulted' ? 'selected' : '' }}>Defaulted</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('notes', $account->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('accounts.show', $account) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
