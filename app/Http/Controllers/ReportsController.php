<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Storage;

class ReportsController extends Controller
{
    /**
     * Show the reports form.
     */
    public function index()
    {

        $currentYear = date('Y');
        $years = [];
        for ($y = $currentYear; $y >= $currentYear - 5; $y--) {
            $years[] = $y;
        }

        return view('reports.index', compact('years'));
    }


    public function generate(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $year = $validated['year'];
        $month = $validated['month'];

        // determine which button was pressed
        $action = $request->input('action', 'filter'); // 'filter' or 'generate'

        $transactions = Transaction::whereYear('transaction_date', $year)
            ->whereMonth('transaction_date', $month)
            ->with('account.customer')
            ->get();

        // compute totals regardless; used only for PDF
        $totalPayments = $transactions->where('type', 'payment')->sum('amount');
        $totalDisbursements = $transactions->where('type', 'disbursement')->sum('amount');

        $pdfName = null;
        if ($action === 'generate') {
            $pdfName = "report_{$year}_{$month}_" . time() . ".pdf";

            $publicDir = public_path('pdf/reports');
            if (! file_exists($publicDir)) {
                mkdir($publicDir, 0755, true);
            }
            $pdfPath = $publicDir . DIRECTORY_SEPARATOR . $pdfName;

            $htmlForPdf = view('reports.pdf', compact('transactions', 'year', 'month', 'totalPayments', 'totalDisbursements'))->render();
            Browsershot::html($htmlForPdf)
                ->noSandbox()
                ->format('A4')
                ->save($pdfPath);
        }

        $currentYear = date('Y');
        $years = [];
        for ($y = $currentYear; $y >= $currentYear - 5; $y--) {
            $years[] = $y;
        }

        return view('reports.index', compact('years', 'transactions', 'year', 'month', 'pdfName', 'totalPayments', 'totalDisbursements', 'action'));
    }
}
