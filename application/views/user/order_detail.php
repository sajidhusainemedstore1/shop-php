<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Order Details</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 30px auto;
            background-color: #f9f9f9;
            padding: 20px;
            color: #333;
        }

        h2 {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
            margin-top: 30px;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: #fff;
            font-weight: 600;
        }

        td {
            font-size: 15px;
        }

        a.back-link {
            display: inline-block;
            margin-bottom: 10px;
            color: #007bff;
            font-size: 15px;
        }

        a.back-link:hover {
            text-decoration: underline;
        }

        .total-row td {
            font-weight: bold;
            background-color: #f1f1f1;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 15px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .invoice-link {
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <a href="<?php echo base_url('user/my_orders') ?>" class="back-link">My Orders</a>

    <h2>ORDER RECEIPT</h2>
    <p><strong>Receipt No:</strong> <?php echo $order['id'] ?></p>
    <p><strong>User:</strong> <?php echo $order['fullname'] ?></p>
    <p><strong>Order Date:</strong> <?php echo date('d M Y', strtotime($order['created_at'])) ?></p>
    <p><strong>Total:</strong> ₹<?php echo number_format($order['total'], 2) ?></p>
    <?php if (isset($order['return_status'])): ?>
        <?php if ($order['return_status'] === 'approved'): ?>
            <div style="background: #dc3545; color: white; padding: 5px 10px; display: inline-block; border-radius: 4px;">
                Your return request approved.
            </div>
            <?php if (!empty($order['return_approve_comment'])): ?>
                <div style="margin-top: 5px;">Approve Comment: <?php echo htmlspecialchars($order['return_approve_comment']) ?></div>
            <?php endif; ?>
        <?php elseif ($order['return_status'] === 'cancelled'): ?>
            <div style="background: #dc3545; color: white; padding: 5px 10px; display: inline-block; border-radius: 4px;">
                Your return request Cancelled.
            </div>
            <?php if (!empty($order['return_cancel_comment'])): ?>
                <div style="margin-top: 5px;">Cancelled Comment: <?php echo htmlspecialchars($order['return_cancel_comment']) ?></div>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>

    <h2>Order Details</h2>
    <form method="post" action="<?php echo base_url('user/return_order'); ?>">

    <?php if (empty($items)): ?>
        <p>Order details not available.</p>
    <?php else: ?>
        <?php 
            $total = 0;
            $discount = isset($order['dis_amount']) ? $order['dis_amount'] : 0;
            $wallet_used = isset($order['wallet_used']) ? $order['wallet_used'] : 0;
            foreach ($items as $item) {
                $total += $item['qty'] * $item['price'];
            }
            $grand_total = max($total - $discount - $wallet_used, 0);
        ?>

    <form method="post" action="<?php echo base_url('user/return_order/' . $order['id']) ?>">
        <a href="<?php echo base_url('user/return_order/' . $order['id']) ?>" onclick="return confirm('Are you sure you want to return this order?')">
            <button type="button">Return Order</button>
        </a>

        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="select_all"> All</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><input type="checkbox" name="return_items[]" value="<?php echo $item['id'] ?>"></td>
                    <td><?php echo $item['name'] ?></td>
                    <td><?php echo $item['qty'] ?></td>
                    <td>₹<?php echo number_format($item['price'], 2) ?></td>
                    <td>₹<?php echo number_format($item['qty'] * $item['price'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td></td>
                    <td colspan="3"><strong>Total:</strong></td>
                    <td><strong>₹<?php echo number_format($total, 2); ?></strong></td>
                </tr>
                <tr class="total-row">
                    <td></td>
                    <td colspan="3"><strong>Discount:</strong></td>
                    <td><strong>- ₹<?php echo number_format($discount, 2); ?></strong></td>
                </tr>
                <tr class="total-row">
                    <td></td>
                    <td colspan="3"><strong>Wallet used:</strong></td>
                    <td><strong>- ₹<?php echo number_format($wallet_used, 2); ?></strong></td>
                </tr>
                <tr class="total-row">
                    <td></td>
                    <td colspan="3"><strong>Grand Total:</strong></td>
                    <td><strong>₹<?php echo number_format($grand_total, 2); ?></strong></td>
                </tr>
            </tfoot>
        </table>

        <div style="text-align: right; margin-top: 20px;">
            <button type="button" onclick="downloadPDF()">Download PDF</button>
        </div>
    </form>

<script>
    document.querySelector("form").addEventListener("submit", function(e) {
        const checkboxes = document.querySelectorAll('input[name="return_items[]"]:checked');
        if (checkboxes.length === 0) {
            alert("Please select at least one item to return.");
            e.preventDefault();
        }
    });
    document.getElementById('select_all').addEventListener('change', function () {
        const checked = this.checked;
        document.querySelectorAll('input[name="return_items[]"]').forEach(cb => cb.checked = checked);
    });
    async function downloadPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        const receiptNo = "#<?php echo $order['id']; ?>";
        const orderDate = "<?php echo date('d M Y', strtotime($order['created_at'])); ?>";
        const username = "<?php echo $order['fullname']; ?>";
        const items = <?php echo json_encode($items); ?>;
        const discount = <?php echo (float) $discount; ?>;
        const walletUsed = <?php echo (float) $wallet_used; ?>;
        const rupee = "₹";

        let total = 0;
        items.forEach(item => {
            total += item.qty * item.price;
        });

        const grandTotal = Math.max(total - discount - walletUsed, 0);
        let y = 15;

        doc.setFontSize(18);
        doc.setFont("helvetica", "bold");
        doc.text("ORDER RECEIPT", 105, y, { align: "center" });

        y += 12;
        doc.setFontSize(12);
        doc.setFont("helvetica", "normal");
        doc.text(`Receipt No: ${receiptNo}`, 15, y);
        y += 7;
        doc.text(`Order Date: ${orderDate}`, 15, y);
        y += 7;
        doc.text(`Customer: ${username}`, 15, y);
        y += 7;
        doc.text(`Total: ${rupee}${total.toFixed(2)}`, 15, y);
        y += 7;
        doc.text(`Discount: -${rupee}${discount.toFixed(2)}`, 15, y);
        y += 7;
        doc.text(`Wallet Used: -${rupee}${walletUsed.toFixed(2)}`, 15, y);
        y += 7;
        doc.text(`Grand Total: ${rupee}${grandTotal.toFixed(2)}`, 15, y);

        y += 12;
        doc.setFont("helvetica", "bold");
        doc.text("Product", 15, y);
        doc.text("Qty", 100, y);
        doc.text("Price", 120, y);
        doc.text("Subtotal", 160, y);

        y += 2;
        doc.setLineWidth(0.5);
        doc.line(15, y, 195, y);
        y += 5;

        doc.setFont("helvetica", "normal");
        items.forEach(item => {
            const name = item.name;
            const qty = item.qty;
            const price = parseFloat(item.price).toFixed(2);
            const subtotal = (qty * item.price).toFixed(2);

            if (y > 270) {
                doc.addPage();
                y = 15;
            }

            doc.text(name, 15, y);
            doc.text(String(qty), 100, y);
            doc.text(`${rupee}${price}`, 120, y);
            doc.text(`${rupee}${subtotal}`, 160, y);
            y += 7;
        });

        y += 10;
        doc.setFont("helvetica", "bold");
        doc.text(`Total: ${rupee}${total.toFixed(2)}`, 15, y);
        doc.save(`Order_Receipt_<?php echo $order['id']; ?>.pdf`);
    }
</script>
<?php endif; ?>

</body>
</html>
