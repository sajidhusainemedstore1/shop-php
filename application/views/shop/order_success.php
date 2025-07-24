<!DOCTYPE html>
<html>
<head>
    <title>Order Success</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            padding: 30px;
            margin: 0;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        h2 {
            color: #28a745;
            margin-bottom: 20px;
            text-align: center;
        }

        p {
            font-size: 1.1rem;
            margin: 10px 0;
        }

        h3 {
            margin-top: 30px;
            color: #007bff;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin-top: 10px;
        }

        ul li {
            padding: 10px;
            background-color: #f1f1f1;
            margin-bottom: 8px;
            border-radius: 6px;
            font-size: 1rem;
        }

        a.button {
            display: inline-block;
            padding: 12px 20px;
            margin-top: 25px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        a.button:hover {
            background-color: #0056b3;
        }

        .summary {
            border-top: 1px solid #ddd;
            margin-top: 20px;
            padding-top: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Order Successful!</h2>

    <div class="summary">
        <p><strong>Order ID:</strong> <?php echo $order['id'] ?></p>
        <p><strong>Total Paid:</strong> ₹<?php echo number_format($order['paid_amount'], 2) ?></p>
        <p><strong>Payment Method:</strong> <?php echo ucfirst($order['payment_method']) ?></p>
    </div>

    <h3>Order Items:</h3>
    <ul>
        <?php foreach ($order_items as $item): ?>
            <li>
                Product ID: <?php echo $item['product_id'] ?> —
                Qty: <?php echo $item['qty'] ?> —
                ₹<?php echo number_format($item['price'], 2) ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="<?php echo base_url('user/home') ?>" class="button">Go to Home</a>
</div>

</body>
</html>
