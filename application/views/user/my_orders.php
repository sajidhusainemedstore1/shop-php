<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <style>
    body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f8f9fa;
    margin: 40px;
    color: #343a40;
    line-height: 1.6;
}

h2 {
    text-align: center;
    color: #2c3e50;
    margin-bottom: 30px;
    font-weight: 700;
}

table {
    width: 90%;
    margin: 0 auto 40px auto;
    border-collapse: collapse;
    background-color: #fff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    border-radius: 6px;
    overflow: hidden;
}

thead {
    background-color: #007bff;
    color: white;
}

th, td {
    padding: 12px 15px;
    border-bottom: 1px solid #dee2e6;
    text-align: center;
}

tbody tr:nth-child(even) {
    background-color: #f1f1f1;
}

tbody tr:hover {
    background-color: #d9eaff;
}

.btn {
    padding: 8px 14px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
    display: inline-block;
    transition: background-color 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn:hover,
.btn:focus {
    background-color: #0056b3;
    outline: none;
}

p {
    text-align: center;
    font-size: 1.1em;
    margin-top: 40px;
}

.home-link {
    display: block;
    width: 90%;
    max-width: 900px;
    margin: 0 auto 20px auto;
    font-weight: 600;
    color: #007bff;
    text-decoration: none;
    font-size: 16px;
    text-align: left;
}

.home-link:hover,
.home-link:focus {
    text-decoration: underline;
    color: #0056b3;
    outline: none;
}
.btn-1 {
    background-color: rgb(36, 182, 207);
    color: white;
    padding: 12px 28px;
    font-weight: bold;
    border-radius: 6px;
    display: inline-block;
    margin-top: 30px;
    text-align: center;
    transition: background-color 0.3s ease;
}
.btn-1:hover {
    background-color: #2980b9;
}

.alert-success {
    background-color: #dff0d8;
    color: #3c763d;
    border: 1px solid #d6e9c6;
}

.alert-danger {
    background-color: #f2dede;
    color: #a94442;
    border: 1px solid #ebccd1;
}

</style>

</head>
<body>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
        <?php echo $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger">
        <?php echo $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>
<h2>My Orders</h2>

<?php if (empty($orders)): ?>
    <p>You have not placed any orders yet.</p>
    <a href="<?php echo base_url('user/home'); ?>" class="btn-1">Go to Shop</a>
<?php else: ?>
    <a href="<?php echo base_url('user/home') ?>" class="home-link"> Home page</a>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Total</th>
                <th>Payment Status</th>
                <th>Date</th>
                <th>Status</th>
                <th>Order Details</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo $order['id'] ?></td>
                <td>₹<?php echo number_format($order['paid_amount'], 2) ?></td>
                <td><?php echo $order['status'] ?></td>
                <td><?php echo date('d M Y H:i A', strtotime($order['created_at'])) ?></td>
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
                <td>
                <a href="<?php echo base_url('user/view_order/' . $order['id']) ?>" class="btn btn-primary">View Details</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

</body>
</html>