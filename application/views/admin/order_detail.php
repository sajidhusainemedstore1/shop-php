<?php $this->load->view("admin/header"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Order Details</title>
<style>
    body, h1, h2, h3, p, table {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        max-width: 800px;
        margin: 40px auto;
        background-color: #f7f9fc;
        padding: 30px;
        color: #333;
        line-height: 1.6;
    }

    h2, h3 {
        color: #222;
        border-bottom: 2px solid #007bff;
        padding-bottom: 5px;
        margin-top: 30px;
        margin-bottom: 15px;
    }

    /* Paragraphs */
    p {
        font-size: 16px;
        margin: 10px 0;
    }

    /* Table Styles */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    th, td {
        border: 1px solid #ddd;
        padding: 12px 10px;
        text-align: center;
        font-size: 15px;
    }

    th {
        background-color: #007bff;
        color: #ffffff;
        font-weight: 600;
        text-transform: uppercase;
    }

    tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    /* Table Footer Highlight */
    tfoot td {
        font-weight: bold;
        background-color: #f1f1f1;
    }

    /* Button Style */
    .btn {
        display: inline-block;
        margin-top: 30px;
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        font-size: 15px;
        font-weight: 500;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        text-align: center;
        transition: background-color 0.3s ease;
        cursor: pointer;
    }

    .btn:hover {
        background-color: #0056b3;
    }

    /* Invoice Link Styling */
    .invoice-link {
        float: right;
    }

    /* Back Link */
    .back-link {
        display: inline-block;
        margin-bottom: 15px;
        color: #007bff;
        text-decoration: none;
        font-weight: 500;
    }

    .btn-action {
        display: inline-block;
        padding: 10px 18px;
        margin-top: 20px;
        margin-right: 10px;
        font-size: 14px;
        font-weight: 500;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        color: #fff;
        transition: background-color 0.3s ease;
    }
    
    .btn-approve {
        background-color: #28a745; /* Green */
    }
    
    .btn-approve:hover {
        background-color: #218838;
    }
    
    .btn-cancel {
        background-color: #dc3545; /* Red */
    }
    
    .btn-cancel:hover {
        background-color: #c82333;
    }
    
    .back-link:hover {
        text-decoration: underline;
    }

    /* Clearfix */
    .clearfix::after {
        content: "";
        display: table;
        clear: both;
    }
    @media screen and (max-width: 600px) {
        table, thead, tbody, th, td, tr {
            display: block;
        }

        th {
            text-align: left;
        }

        td {
            text-align: right;
            padding-left: 50%;
            position: relative;
        }

        td::before {
            content: attr(data-label);
            position: absolute;
            left: 10px;
            top: 12px;
            font-weight: bold;
            text-align: left;
        }
    }
</style>
</head>
<body>
    <a href="<?php echo base_url('admin/order_list') ?>" class="back-link"> My Orders</a>
    <?php if ($this->session->flashdata('success')): ?>
        <p style="color: green;"><?php echo $this->session->flashdata('success'); ?></p>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <p style="color: red;"><?php echo $this->session->flashdata('error'); ?></p>
    <?php endif; ?>

    <?php $return_status = isset($order['return_status']) ? $order['return_status'] : 'none'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <h2>ORDER RECEIPT</h2>
    <p><strong>Receipt No:</strong> <?php echo $order['id'] ?></h2></p>
    <p><strong>User:</strong> <?php echo $order['fullname'] ?></p>
    <p><strong>Order Date:</strong> <?php echo date('d M Y', strtotime($order['created_at'])) ?></p>
    <p><strong>Total:</strong> ₹<?php echo number_format($order['total'], 2) ?></p>
    <h2>Order Details</h2>
    <?php if (empty($items)): ?>
        <p>Order details not available.</p>
    <?php else: ?>
        <?php 
            $total = 0;
            $discount = isset($order['dis_amount']) ? $order['dis_amount'] : 0;
            $wallet_used = isset($order['wallet_used']) ? $order['wallet_used'] : 0;
            $grand_total = max($order['total'], 0);
        ?>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($items as $item): 
                $subtotal = $item['qty'] * $item['price'];
                $total += $subtotal;
            ?>
            <tr>
                <td><?php echo $item['name'] ?></td>
                <td><?php echo $item['qty'] ?></td>
                <td>₹<?php echo number_format($item['price'], 2) ?></td>
                <td>₹<?php echo number_format($subtotal, 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <?php
                $total = $order['total'];
                $discount = isset($order['dis_amount']) ? $order['dis_amount'] : 0;
                $wallet = isset($order['wallet_used']) ? $order['wallet_used'] : 0;
                $final_total = $total - $discount - $wallet;
            ?>
            <tr>
                <td colspan="3" style="text-align:right;"><strong>Total:</strong></td>
                <td>₹<?php echo number_format($total, 2) ?></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:right;"><strong>Discount:</strong></td>
                <td>- ₹<?php echo number_format($discount, 2) ?></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:right;"><strong>Wallet used:</strong></td>
                <td>- ₹<?php echo number_format($wallet, 2) ?></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:right;"><strong>Grand Total:</strong></td>
                <td><strong>₹<?php echo number_format($final_total, 2) ?></strong></td>
            </tr>
        </tfoot>
    </table>
    <?php $status = isset($order['status']) ? $order['status'] : ''; ?>
    <div class="clearfix">
        <?php if ($status != 'Delivered' && $status != 'Cancelled'): ?>
            <a href="<?php echo base_url('admin/deliverd_order/' . $order['id']) ?>" 
               class="btn" 
               onclick="return confirm('Mark this order as Delivered?');">
               Deliver
            </a>

            <a href="<?php echo base_url('admin/cancel_order/' . $order['id']) ?>" 
               class="btn" 
               onclick="return confirm('Cancel this order?');">
               Cancel Order
            </a>
        <?php else: ?>
            <p><strong>Status:</strong> <?php echo ucfirst($status); ?></p>
        <?php endif; ?>
        <a href="<?php echo base_url('admin/invoice/' . $order['id']) ?>" target="_blank" class="btn invoice-link">Print (PDF)</a>

        <?php if ($show_buttons): ?>
            <form method="post" action="<?php echo base_url('admin/approve_return/' . $order['id']) ?>" style="display:inline;">
                <button type="submit" class="btn-action btn-approve">Approve Return</button>
            </form>

            <form method="post" action="<?php echo base_url('admin/cancel_return/' . $order['id']) ?>" style="display:inline;">
                <button type="submit" class="btn-action btn-cancel">Cancel Return</button>
            </form>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</body>
</html>
<?php $this->load->view("admin/footer"); ?>