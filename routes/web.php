<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SoaController;
use App\Http\Controllers\PdfController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {

    $totalCustomers = \App\Models\Customer::count();
    $totalAccounts = \App\Models\Account::count();
    $activeAccounts = \App\Models\Account::where('status', 'active')->count();
    $totalPrincipal = \App\Models\Account::sum('principal_amount');
    $totalBalance = \App\Models\Account::sum('balance');
    $recentTransactions = \App\Models\Transaction::with('account.customer')->latest()->take(5)->get();

    return view('dashboard', compact(
        'totalCustomers',
        'totalAccounts',
        'activeAccounts',
        'totalPrincipal',
        'totalBalance',
        'recentTransactions'
    ));

})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/customers/pdf', [PdfController::class, 'generateCustomersPdf'])->name('customers.pdf');

    Route::get('/soa', [SoaController::class, 'soaGeneration'])->name('soa.index');
    Route::get('/soa/generate-all', [SoaController::class, 'generateAllSOAs'])->name('soa.generateAll');

    Route::resource('customers', CustomerController::class);
    Route::resource('accounts', AccountController::class);
    Route::resource('transactions', TransactionController::class);
});

require __DIR__.'/auth.php';
