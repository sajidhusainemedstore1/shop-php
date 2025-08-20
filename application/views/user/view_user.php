<?php $this->load->view("user/header"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>User Wallet Details</title>
    <style>
        .container {
            font-family: Arial, sans-serif;
            background: #f7f9fc;
            padding: 20px;
        }
        h2 {
            margin-bottom: 10px;
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: #fff;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #06979A;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Wallet Balance: ₹<?php echo number_format((float)$user['wallet_balance'], 2) ?></h2>

        <h3>Transaction History</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Amount</th>
                    <th>Transactions</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($transactions)): ?>
                    <?php foreach ($transactions as $i => $txn): ?>
                        <tr>
                            <td><?php echo $txn['id'] ?></td>
                            <td>₹<?php echo number_format((float)$txn['amount'], 2) ?></td>
                            <td>
                                <?php
                                    $desc = $txn['description'];
                                    if (strpos($desc, 'Order No.') !== false) {
                                        echo (strpos($txn['amount'], '-') !== false ? 'Your wallet has been debited. ' : 'Your wallet has been credited. ') . $desc;
                                    } else {
                                        echo htmlspecialchars($desc);
                                    }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($txn['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">No transactions found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php $this->load->view("user/footer"); ?>