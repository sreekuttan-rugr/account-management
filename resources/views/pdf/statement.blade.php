<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Statement</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>Account Statement</h2>
    <p><strong>Account Name:</strong> {{ $account->account_name }}</p>
    <p><strong>Account Number:</strong> {{ $account->account_number }}</p>
    <p><strong>Balance:</strong> {{ $account->balance }}</p>

    <h3>Transactions</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $transaction->created_at }}</td>
                <td>{{ $transaction->type }}</td>
                <td>${{ $transaction->amount }}</td>
                <td>{{ $transaction->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
