<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

        foreach ($accounts as $account) {
            Log::info("Generating SOA for Account ID: {$account->id}, Account Number: {$account->account_number}");

            $mail = new \App\Mail\SoaManagement($account);

            // Using `Mail::later` to send the email after a 5-second delay
            Mail::to($account->customer->email)
                ->later(now()->addSeconds(5), $mail);
        }

        return redirect()->route('soa.index')->with('status', 'All SOAs have been generated successfully.');
    }

    private function getAccountsForSOA()
    {
        return Account::whereDay('start_date', 20)->get();
        // return Account::whereDay('start_date', \Carbon\Carbon::now()->addDays(10)->day)->get();
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
