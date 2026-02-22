<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">

    <table align="center" width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; margin-top: 30px; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <tr>
            <td style="background-color: #4CAF50; padding: 20px; color: #ffffff; text-align: center;">
                <h2 style="margin: 0;">Transaction Confirmation</h2>
            </td>
        </tr>

        <tr>
            <td style="padding: 20px;">
                <p>Hello <strong>{{ $transaction->account->customer->name }}</strong>,</p>
                <p>Your transaction has been successfully processed. Here are the details:</p>

                <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse: collapse; margin-top: 20px;">
                    <tr style="background-color: #f9f9f9;">
                        <td style="border: 1px solid #ddd;"><strong>Transaction #</strong></td>
                        <td style="border: 1px solid #ddd;">{{ $transaction->transaction_number }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; background-color: #f9f9f9;"><strong>Type</strong></td>
                        <td style="border: 1px solid #ddd;">
                            <span style="padding: 3px 8px; border-radius: 5px; color: #fff; background-color: {{ $transaction->type === 'payment' ? '#28a745' : '#6f42c1' }};">
                                {{ ucfirst($transaction->type) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd;"><strong>Amount</strong></td>
                        <td style="border: 1px solid #ddd;">₱{{ number_format($transaction->amount, 2) }}</td>
                    </tr>
                    <tr style="background-color: #f9f9f9;">
                        <td style="border: 1px solid #ddd;"><strong>Date</strong></td>
                        <td style="border: 1px solid #ddd;">{{ $transaction->transaction_date->format('F d, Y') }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; background-color: #f9f9f9;"><strong>Balance After</strong></td>
                        <td style="border: 1px solid #ddd;">₱{{ number_format($transaction->balance_after, 2) }}</td>
                    </tr>
                </table>

                <p style="margin-top: 20px;">Thank you for your transaction!</p>
            </td>
        </tr>

        <tr>
            <td style="background-color: #f4f4f4; text-align: center; padding: 15px; color: #777;">
                &copy; {{ date('Y') }} Your Company Name. All rights reserved.
            </td>
        </tr>
    </table>

</body>
</html>
