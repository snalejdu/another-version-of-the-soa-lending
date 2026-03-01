<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monthly Transactions PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #555; padding: 4px 6px; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h1>Monthly Transaction Report</h1>
    <h2>{{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }}</h2>

    @if($transactions->isEmpty())
        <p>No transactions found for this period.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Account No</th>
                    <th>Disbursement</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $txn)
                    <tr>
                        <td>{{ $txn->account->customer->name ?? '-' }}</td>
                        <td>{{ $txn->account->account_number ?? '-' }}</td>
                        <td style="text-align:right;">
                            @if($txn->type === 'disbursement') ₱{{ number_format($txn->amount,2) }} @endif
                        </td>
                        <td style="text-align:right;">
                            @if($txn->type === 'payment') ₱{{ number_format($txn->amount,2) }} @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="font-weight:bold;">
                    <td colspan="2">Totals</td>
                    <td style="text-align:right;">₱{{ number_format($totalDisbursements,2) }}</td>
                    <td style="text-align:right;">₱{{ number_format($totalPayments,2) }}</td>
                </tr>
            </tfoot>
        </table>
    @endif
</body>
</html>
