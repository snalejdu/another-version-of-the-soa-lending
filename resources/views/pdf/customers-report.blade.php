<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Customers Report</title>

    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            padding: 30px;
            color: #333;
        }

        .header{
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1{
            margin: 0;
            font-size: 28px;
        }

        .header p{
            margin: 5px 0;
            color: #666;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        table thead{
            background-color: #1f2937;
            color: white;
        }

        th, td{
            padding: 12px;
            border: 1px solid #ddd;
        }

        tbody tr:nth-child(even){
            background-color: #f9fafb;
        }

        .footer{
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }

        .summary{
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>

</head>
<body>

<div class="header">
    <h1>Customers Report</h1>
    <p>Generated on {{ now()->format('F d, Y h:i A') }}</p>
</div>

<div class="summary">
    Total Customers: {{ $customers->count() }}
</div>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>User Account</th>
            <th>Accounts</th>
        </tr>
    </thead>

    <tbody>
        @forelse($customers as $customer)
            <tr>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone ?? 'N/A' }}</td>
                <td>
                    {{ $customer->user ? 'Yes' : 'No' }}
                </td>
                <td>
                    {{ $customer->accounts->count() }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align:center;">
                    No customers found
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    Confidential Customer Report
</div>

</body>
</html>
