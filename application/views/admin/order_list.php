<?php $this->load->view('admin/header'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order List</title>
<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f9f9f9;
        color: #333;
        line-height: 1.6;
    }

    h2 {
        text-align: center;
        margin: 40px 0 20px;
        font-size: 2em;
        color: #333;
    }

    .container {
        width: 95%;
        max-width: 1200px;
        margin: 0 auto;
        padding-bottom: 40px;
    }

    .export-btn, .invoice-link {
        display: inline-block;
        background-color: #007bff;
        color: #fff;
        padding: 10px 18px;
        text-decoration: none;
        border-radius: 5px;
        margin: 15px 5px;
        transition: background-color 0.3s ease;
    }

    .export-btn:hover, .invoice-link:hover {
        background-color: #0056b3;
    }

    .invoice-link {
        float: right;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        overflow-x: auto;
        border-radius: 6px;
    }

    th, td {
        padding: 14px 16px;
        text-align: center;
        border: 1px solid #ddd;
        font-size: 0.95rem;
    }

    th {
        background-color: #007bff;
        color: #fff;
        font-weight: 600;
    }

    tr:nth-child(even) {
        background-color: #f1f5f9;
    }

    tr:hover {
        background-color: #eef2f7;
    }

    a {
        color: #007bff;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    .no-orders {
        text-align: center;
        padding: 30px;
        font-style: italic;
        color: #999;
        font-size: 1.1em;
    }

    @media (max-width: 768px) {
        table, thead, tbody, th, td, tr {
            display: block;
        }

        thead {
            display: none;
        }

        tr {
            margin-bottom: 15px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            padding: 10px;
        }

        td {
            padding: 10px;
            text-align: right;
            position: relative;
        }

        td::before {
            content: attr(data-label);
            position: absolute;
            left: 10px;
            top: 10px;
            font-weight: bold;
            text-align: left;
            text-transform: capitalize;
        }
    }

</style>
</head>
<body>
    <h2>All Orders</h2>
    <div class="container">
        <a href="<?php echo base_url('admin/export_orders_excel') ?>" class="btn invoice-link">Export All Orders to Excel</a>

        <table>
            <thead>
                <tr>
                    <th>Receipt No</th>
                    <th>Username</th>
                    <th>Total (₹)</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($orders)): ?>
                    <?php foreach($orders as $order): ?>
                        <tr>
                            <td data-label="Receipt No"><?php echo $order['id'] ?></td>
                            <td data-label="Username"><?php echo $order['fullname'] ?></td>
                            <td data-label="Total (₹)"><?php echo number_format($order['total'], 2) ?></td>
                            <td data-label="Order Date"><?php echo date('d M Y', strtotime($order['created_at'])) ?></td>
                            <td>
                                <?php if (isset($order['return_status'])): ?>
                                    <?php if ($order['return_status'] === 'approved'): ?>
                                        <div style="background: #1d8b35ff; color: white; padding: 5px 10px; display: inline-block; border-radius: 4px;">
                                            Your return request approved.
                                        </div>
                                        <?php if (!empty($order['return_approve_comment'])): ?>
                                            <div style="margin-top: 5px;">Approve Comment: <?php echo htmlspecialchars($order['return_approve_comment']) ?></div>
                                        <?php endif; ?>
                                    <?php elseif ($order['return_status'] === 'cancelled'): ?>
                                        <div style="background: #dc3545; color: white; padding: 5px 10px; display: inline-block; border-radius: 4px;">
                                            Your return Request Cancelled.
                                        </div>
                                        <?php if (!empty($order['return_cancel_comment'])): ?>
                                            <div style="margin-top: 5px;">Cancelled Comment: <?php echo htmlspecialchars($order['return_cancel_comment']) ?></div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td data-label="Action">
                                <a href="<?php echo base_url('admin/order_detail/' . $order['id']) ?>">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="no-orders">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php $this->load->view('admin/footer'); ?>