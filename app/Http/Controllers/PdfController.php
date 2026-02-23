<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Spatie\LaravelPdf\Facades\Pdf;

class PdfController extends Controller
{
    public function generateCustomersPdf()
    {
        $customers = Customer::with(['accounts', 'user'])->get();

        $fileName = 'customers_report_' . now()->timestamp . '.pdf';


        $pdfPath = storage_path('app/pdf');

        if (!is_dir($pdfPath)) {
            mkdir($pdfPath, 0755, true);
        }

        Pdf::view('pdf.customers-report', compact('customers'))
            ->format('A4')
            ->save($pdfPath . '/' . $fileName);

        return back()->with('success', 'PDF saved successfully!');

        //  return response()->download($pdfPath . '/' . $fileName);
        // this will save and download the file
    }
}
