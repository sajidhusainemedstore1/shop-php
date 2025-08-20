<?php $this->load->view('admin/header'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Coupons List</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color:#06979A;
            color: white;
        }
        a.button {
            padding: 5px 10px;
            background-color: #06979A;
            color: white;
            text-decoration: none;
            border-radius: 3px;
            margin-right: 5px;
        }
        a.button1 {
            padding: 5px 10px;
            background-color:rgb(29, 185, 89);
            color: white;
            text-decoration: none;
            border-radius: 3px;
            margin-right: 5px;
        }
        a.button.delete {
            background-color: #06979A;
        }
        .action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.action-buttons a.button {
    flex: 1;
    text-align: center;
}
    </style>
</head>
<body>
    <?php if($this->session->flashdata('success')): ?>
        <p style="color:green;"><?php echo $this->session->flashdata('success'); ?></p>
    <?php endif; ?>

    <h1>Coupons List</h1><br>

    <a href="<?php echo base_url('admin/add_coupon'); ?>" class="button">Add New Coupon</a>

    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Type</th>
                <th>Discount (%)</th>
                <th>Discount Price</th>
                <th>Number of Users</th>
                <th>Redemption Limit</th>
                <th>Start Date/Time</th>
                <th>Expiry Date/Time</th>
                <th>Minimum Purchase Amount</th>
                <th>Maximum Discount Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($coupons)): ?>
                <?php foreach($coupons as $coupon): ?>
                    <tr>
                        <td><?php echo ($coupon['code']); ?></td>
                        <td><?php echo ucfirst($coupon['type']); ?></td>
                        <td><?php echo number_format($coupon['value'], 2); ?></td>
                        <td><?php echo number_format($coupon['dis_amount'], 2); ?></td>
                        <td><?php echo $coupon['number_of_users'] ?? '-'; ?></td>
                        <td><?php echo $coupon['redemption_limit'] . ' ' . $coupon['custom_redemption_limit'] ?? '-'; ?></td>
                        <td>
                            <?php echo date('d M Y', strtotime($coupon['start_date'])) . ' ' . date('h:i A', strtotime($coupon['start_time'])); ?>
                        </td>
                        <td>
                            <?php echo date('d M Y', strtotime($coupon['expiry_date'])) . ' ' . date('h:i A', strtotime($coupon['expiry_time'])); ?>
                        </td>
                        <td>
                            <?php echo isset($coupon['min_purchase_amount']) ? number_format($coupon['min_purchase_amount'], 2) : '-'; ?>
                        </td>
                        <td>
                            <?php echo isset($coupon['max_discount_amount']) ? number_format($coupon['max_discount_amount'], 2) : '-'; ?>
                        </td>
                        <?php
                            date_default_timezone_set('Asia/Kolkata');

                            $expiry_str = $coupon['expiry_date'] . ' ' . $coupon['expiry_time'];
                            $expiry_timestamp = strtotime($expiry_str);
                            $now_timestamp = time();

                            if ($expiry_timestamp === false) {
                                $is_expired = true;
                            } else {
                                $is_expired = $expiry_timestamp < $now_timestamp;
                            }
                        ?>
                        <td><?php echo $is_expired ? 'Expired' : 'Active'; ?></td>

                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo base_url('admin/edit_coupon/'.$coupon['code']); ?>" class="button">Edit</a>
                                <a href="<?php echo base_url('admin/delete_coupon/'.$coupon['code']); ?>" class="button delete" onclick="return confirm('Are you sure to delete this coupon?');">Delete</a>
                            </div>
                        </td>

                    </tr>

                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">No coupons found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
<?php $this->load->view('admin/footer'); ?>