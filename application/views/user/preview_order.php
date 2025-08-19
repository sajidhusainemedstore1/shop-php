<?php $this->load->view("user/header"); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Preview</title>
    <style>
        .container {
            font-family: 'Segoe UI', sans-serif;
            background: #f8f9fa;
            margin: 40px;
        }

        .preview-box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            max-width: 900px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .address, .payment {
            width: 48%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        table th, table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .summary td {
            text-align: right;
        }

        .summary td:first-child {
            text-align: left;
        }

        .total-row {
            font-weight: bold;
            font-size: 18px;
        }

        .btn-confirm {
            margin-top: 30px;
            display: block;
            width: 200px;
            padding: 12px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-confirm:hover {
            background-color: #0056b3;
        }

        .remark-input {
            width: 100%;
            padding: 8px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .wallet-box {
            margin-top: 20px;
            padding: 15px;
            background: #f1f1f1;
            border-radius: 6px;
        }

        .wallet-box input[type="submit"] {
            margin-left: 10px;
            padding: 5px 10px;
        }
    </style>
</head>
<body>

<div class="preview-box">
    <h2>PREVIEW ORDER</h2>

    <form id="confirm-order-form" method="post" action="<?php echo base_url('shop/buy') ?>">
        <div class="section">
            <!-- Address Section -->
            <div class="address">
                <h4>Delivery Address</h4>
                <p><strong>Name:</strong> <?php echo !empty($address['name']) ? $address['name'] : '' ?></p>
                <p><strong>Mobile No:</strong> <?php echo !empty($address['mobile']) ? $address['mobile'] : '' ?></p>
                <p><strong>Email:</strong> <?php echo !empty($address['email']) ? $address['email'] : '' ?></p>
                <p><strong>Address:</strong>
                    <?php if (!empty($address)): ?>
                        <?php echo $address['address'] ?>, <?php echo $address['city'] ?>, <?php echo $address['state'] ?> - <?php echo $address['pincode'] ?>
                    <?php else: ?>
                        No default address found.
                    <?php endif; ?>
                </p>
            </div>

            <!-- Payment Section -->
            <div class="payment">
                <h4>Payment Type</h4>
                <label>
                    <input type="radio" name="payment_method" value="COD" checked> COD
                </label>
                <label style="margin-left: 20px;">
                    <input type="radio" name="payment_method" value="Online"> Pay Online
                </label>
            </div>
        </div>

        <!-- Cart Items -->
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($cart_items)): ?>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?php echo $item['name'] ?></td>
                            <td><?php echo $item['qty'] ?></td>
                            <td>₹<?php echo number_format($item['price'], 2) ?></td>
                            <td>₹<?php echo number_format($item['subtotal'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Order Summary -->
        <table class="summary">
            <tr>
                <td>Sub Total:</td>
                <td>₹<?php echo number_format($subtotal, 2) ?></td>
            </tr>

            <?php if (!empty($discount) && $discount > 0): ?>
            <tr>
                <td>Promocode Discount:</td>
                <td>- ₹<?php echo number_format($discount, 2) ?></td>
            </tr>
            <?php endif; ?>

            <?php if ($this->session->userdata('use_wallet') && !empty($wallet_amount) && $wallet_amount > 0): ?>
            <tr>
                <td>Wallet Used:</td>
                <td>- ₹<?php echo number_format($wallet_amount, 2) ?></td>
            </tr>
            <?php endif; ?>

            <tr>
                <td>Delivery Charges:</td>
                <td>+ ₹<?php echo number_format($delivery_charge ?? 0, 2) ?></td>
            </tr>

            <tr class="total-row">
                <td>Total Amount:</td>
                <td>₹<?php echo number_format($final_total, 2) ?></td>
            </tr>
        </table>

        <button type="submit" class="btn-confirm">Confirm Order</button>
    </form>
</div>

</body>
</html>
<?php $this->load->view("user/footer"); ?>