<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Lending Investment Management System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased dark:bg-gray-900 dark:text-white/50">
        <div class="bg-gray-50 text-black/50 dark:bg-gray-900 dark:text-white/50">
            <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-indigo-500 selection:text-white">
                <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                    <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                        <div class="flex lg:justify-center lg:col-start-2">
                            <h1 class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">LendingHub</h1>
                        </div>
                        @if (Route::has('login'))
                            <nav class="-mx-3 flex flex-1 justify-end">
                                @auth
                                    <a
                                        href="{{ url('/dashboard') }}"
                                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-indigo-600 focus:outline-none focus-visible:ring-indigo-500 dark:text-white dark:hover:text-indigo-400 dark:focus-visible:ring-white"
                                    >
                                        Dashboard
                                    </a>
                                @else
                                    <a
                                        href="{{ route('login') }}"
                                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-indigo-600 focus:outline-none focus-visible:ring-indigo-500 dark:text-white dark:hover:text-indigo-400 dark:focus-visible:ring-white"
                                    >
                                        Log in
                                    </a>

                                    @if (Route::has('register'))
                                        <a
                                            href="{{ route('register') }}"
                                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-indigo-600 focus:outline-none focus-visible:ring-indigo-500 dark:text-white dark:hover:text-indigo-400 dark:focus-visible:ring-white"
                                        >
                                            Register
                                        </a>
                                    @endif
                                @endauth
                            </nav>
                        @endif
                    </header>

                    <main class="mt-6">
                        {{-- Hero Section --}}
                        <div class="text-center mb-16">
                            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                                Lending Investment Management System
                            </h2>
                            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                                Streamline your lending operations with powerful tools for customer management, loan tracking, and financial oversight.
                            </p>
                        </div>

                        {{-- Features Grid --}}
                        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8 mb-12">
                            <a
                                href="{{ route('customers.index') }}"
                                class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-indigo-500/50 focus:outline-none focus-visible:ring-indigo-500 md:row-span-2 lg:p-10 lg:pb-10 dark:bg-gray-800 dark:ring-gray-700 dark:hover:text-white/70 dark:hover:ring-indigo-500/50 dark:focus-visible:ring-indigo-500"
                            >
                                <div class="relative flex items-center gap-6 lg:items-end w-full">
                                    <div class="flex items-start gap-6 lg:flex-col flex-1">
                                        <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-indigo-500/10 sm:size-16">
                                            <svg class="size-5 sm:size-6 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </div>
                                        <div class="pt-3 sm:pt-5 lg:pt-0">
                                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Customer Management</h2>
                                            <p class="mt-4 text-sm/relaxed text-gray-600 dark:text-gray-400">
                                                Manage customer profiles, track borrowing history, and maintain comprehensive records. View all customers, add new borrowers, and monitor their loan activities in one centralized location.
                                            </p>
                                        </div>
                                    </div>
                                    <svg class="size-6 shrink-0 stroke-indigo-600 dark:stroke-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"/>
                                    </svg>
                                </div>
                            </a>

                            <a
                                href="{{ route('accounts.index') }}"
                                class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-indigo-500/50 focus:outline-none focus-visible:ring-indigo-500 lg:pb-10 dark:bg-gray-800 dark:ring-gray-700 dark:hover:text-white/70 dark:hover:ring-indigo-500/50 dark:focus-visible:ring-indigo-500"
                            >
                                <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-emerald-500/10 sm:size-16">
                                    <svg class="size-5 sm:size-6 text-emerald-600 dark:text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>

                                <div class="pt-3 sm:pt-5 flex-1">
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Loan Accounts</h2>
                                    <p class="mt-4 text-sm/relaxed text-gray-600 dark:text-gray-400">
                                        Track all loan accounts with automatic amortization calculations, interest rates, payment schedules, and account status monitoring (active, paid, overdue, defaulted).
                                    </p>
                                </div>

                                <svg class="size-6 shrink-0 self-center stroke-emerald-600 dark:stroke-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"/>
                                </svg>
                            </a>

                            <a
                                href="{{ route('transactions.index') }}"
                                class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-indigo-500/50 focus:outline-none focus-visible:ring-indigo-500 lg:pb-10 dark:bg-gray-800 dark:ring-gray-700 dark:hover:text-white/70 dark:hover:ring-indigo-500/50 dark:focus-visible:ring-indigo-500"
                            >
                                <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-blue-500/10 sm:size-16">
                                    <svg class="size-5 sm:size-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </div>

                                <div class="pt-3 sm:pt-5 flex-1">
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Transaction History</h2>
                                    <p class="mt-4 text-sm/relaxed text-gray-600 dark:text-gray-400">
                                        Record and monitor all financial transactions including loan disbursements and payments. Automatic balance updates ensure accurate financial tracking at all times.
                                    </p>
                                </div>

                                <svg class="size-6 shrink-0 self-center stroke-blue-600 dark:stroke-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"/>
                                </svg>
                            </a>

                            <div class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] lg:pb-10 dark:bg-gray-800 dark:ring-gray-700">
                                <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-purple-500/10 sm:size-16">
                                    <svg class="size-5 sm:size-6 text-purple-600 dark:text-purple-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>

                                <div class="pt-3 sm:pt-5">
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Financial Analytics</h2>
                                    <p class="mt-4 text-sm/relaxed text-gray-600 dark:text-gray-400">
                                        Access comprehensive dashboards with real-time statistics, portfolio performance metrics, and detailed financial reports to make informed lending decisions.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- CTA Section --}}
                        @auth
                            <div class="text-center py-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg">
                                <h3 class="text-2xl font-bold text-white mb-4">Ready to Get Started?</h3>
                                <p class="text-indigo-100 mb-6">Access your dashboard to manage your lending operations</p>
                                <a href="{{ url('/dashboard') }}" class="inline-block bg-white text-indigo-600 px-6 py-3 rounded-md font-semibold hover:bg-indigo-50 transition duration-300">
                                    Go to Dashboard
                                </a>
                            </div>
                        @else
                            <div class="text-center py-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg">
                                <h3 class="text-2xl font-bold text-white mb-4">Ready to Get Started?</h3>
                                <p class="text-indigo-100 mb-6">Log in to access the lending management system</p>
                                <div class="space-x-4">
                                    <a href="{{ route('login') }}" class="inline-block bg-white text-indigo-600 px-6 py-3 rounded-md font-semibold hover:bg-indigo-50 transition duration-300">
                                        Log In
                                    </a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="inline-block bg-indigo-700 text-white px-6 py-3 rounded-md font-semibold hover:bg-indigo-800 transition duration-300">
                                            Register
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endauth
                    </main>

                    <footer class="py-16 text-center text-sm text-black dark:text-white/70">
                        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                    </footer>
                </div>
            </div>
        </div>
    </body>
</html>
