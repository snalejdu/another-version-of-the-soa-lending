<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Support\Facades\Log;
use App\Jobs\SoaDelay;

class SoaController extends Controller
{
    public function soaGeneration()
    {
        $accounts = $this->getAccountsForSOA();

        return view('soa.index', [
            'accounts' => $accounts
        ]);
    }

    public function generateAllSOAs()
    {
        $accounts = $this->getAccountsForSOA();
        $delaySeconds = 0; // start at 0 for the first email

        foreach ($accounts as $account) {
            Log::info("Queueing SOA for Account ID: {$account->id}");

            // Dispatch the job with a staggered delay
            SoaDelay::dispatch($account)
                ->delay(now()->addSeconds($delaySeconds));

            // Increase delay by 6 seconds for the next account
            $delaySeconds += 6;
        }

        return redirect()
            ->route('soa.index')
            ->with('status', count($accounts) . ' SOA jobs queued, 1 email every 6 seconds.');
    }

    private function getAccountsForSOA()
    {
        // Get accounts whose start_date day is 15
        return Account::whereDay('start_date', 15)->get();
    }
}


// namespace App\Http\Controllers;

// use App\Jobs\SoaDelay;  // Add the import for the SoaDelay job
// use Illuminate\Http\Request;
// use App\Models\Account;
// use Illuminate\Support\Facades\Log;

// class SoaController extends Controller
// {
//     public function soaGeneration()
//     {
//         $accounts = $this->getAccountsForSOA();

//         return view('soa.index', [
//             'accounts' => $accounts
//         ]);
//     }

//     public function generateAllSOAs()
//     {
//         $accounts = $this->getAccountsForSOA();

//         foreach ($accounts as $account) {
//             Log::info("Generating SOA for Account ID: {$account->id}, Account Number: {$account->account_number}");

//             // Dispatch the SoaDelay job to send the SOA email with a delay
//             SoaDelay::dispatch($account->id)  // Pass only the account ID
//                 ->delay(now()->addSeconds(5));  // Delay for 5 seconds

//             Log::info("SoaDelay job dispatched for Account ID: {$account->id}");
//         }

//         return redirect()->route('soa.index')->with('status', 'All SOAs have been generated successfully.');
//     }

//     private function getAccountsForSOA()
//     {
//         return Account::whereDay('start_date', 20)->get();
//         // return Account::whereDay('start_date', \Carbon\Carbon::now()->addDays(10)->day)->get();
//     }
// }
