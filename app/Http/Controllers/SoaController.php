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

        return view('soa.index',[
            'accounts' => $accounts
        ]);
    }

    public function generateAllSOAs()
    {
        $accounts = $this->getAccountsForSOA();

        foreach ($accounts as $account) {

             $mail = new \App\Mail\SoaManagement($account);
             Mail::to($account->customer->email)->queue($mail);

            // Log::info("Generating SOA for Account ID: {$account->id}, Account Number: {$account->account_number}");
            // Log::info("Generating SOA for Account ID: {$account->id}, Account Number: {$account->account_number}");

        }

        return redirect()->route('soa.index')->with('status', 'All SOAs have been generated successfully.');
    }

    private function getAccountsForSOA()
    {
        // return Account::whereDay('start_date', now()->day)->get();
        // return Account::whereDay('start_date', 23)->get();
        // return Account::whereDay('start_date', 23)->get();
            return Account::whereDay('start_date', \Carbon\Carbon::now()->addDays(10)->day)->get();
    }
}
