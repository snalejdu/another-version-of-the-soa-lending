<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Mail\SoaManagement;
use Illuminate\Support\Facades\Mail;

class SoaController extends Controller
{

    public function soaGeneration()
    {
        $accounts = Account::with('customer')
            ->whereDay('start_date', now()->addDays(10)->day)
            ->get();

        return view('soa.index', [
            'accounts' => $accounts
        ]);
    }


    public function generateAllSOAs()
    {
        Account::with('customer')
            ->whereDay('start_date', now()->addDays(10)->day)
            ->chunk(50, function ($accounts) {

// i use chunk() to limit account that Render

                foreach ($accounts as $account) {
                    Mail::to($account->customer->email)
                        ->queue(new SoaManagement($account));
                }

            });

        return redirect()
            ->route('soa.index')
            ->with('status', 'All SOAs have been queued successfully.');
    }
}

// return Account::whereDay('start_date', 23)->get();
