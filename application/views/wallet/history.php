<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Wallet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f9fc;
            padding: 20px;
            color: #333;
        }

        h2, h3 {
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: #fff;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }

        table th, table td {
            border: 1px solid #ddd;
            text-align: left;
            padding: 10px;
        }

        table th {
            background-color: #3498db;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .wallet-container {
            margin-top: 30px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            max-width: 400px;
            box-shadow: 0 0 8px rgba(0,0,0,0.05);
        }

        .wallet-recharge input[type="number"] {
            padding: 10px;
            width: calc(100% - 100px);
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
        }

        .wallet-recharge button {
            padding: 10px 20px;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .wallet-recharge button:hover {
            background-color: #219150;
        }
    </style>
</head>
<body>
    <h2>Wallet Balance: ₹<?php echo number_format($user['wallet_balance'], 2); ?></h2>

    <h3>Transaction History</h3>
    <table>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Description</th>
        </tr>
        <?php foreach ($transactions as $txn): ?>
        <tr>
            <td><?php echo $txn['created_at'] ?></td>
            <td><?php echo ucfirst($txn['type']) ?></td>
            <td>₹<?php echo number_format($txn['amount'], 2) ?></td>
            <td><?php echo $txn['description'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="wallet-container">
        <div class="wallet-recharge">
            <form method="post" action="<?php echo base_url('wallet/recharge'); ?>">
                <input type="number" name="amount" placeholder="Amount" required min="1">
                <button type="submit">Recharge</button>
            </form>
    </div>
</body>
</html>