<?php $this->load->view("user/header"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 30px;
        }

        #cart-icon {
            position: fixed;
            top: 20px;
            right: 30px;
            font-size: 24px;
            cursor: pointer;
            color: #2980b9;
            z-index: 1000;
        }

        #cart-count-badge {
            position: absolute;
            top: -8px;
            right: -10px;
            background: red;
            color: white;
            border-radius: 50%;
            padding: 3px 7px;
            font-size: 12px;
            display: none;
            font-weight: bold;
            min-width: 20px;
            text-align: center;
        }

        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        thead {
            background-color: #06979A;
            color: #fff;
        }

        th, td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        tbody tr:hover {
            background-color: #f1f9ff;
        }

        p {
            font-size: 1.2rem;
            margin-top: 20px;
        }

        .alert {
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            width: 100%;
            max-width: 700px;
            margin: 0 auto 20px;
            font-weight: 600;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        select.qty-select {
            padding: 6px 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            text-decoration: none;
            color: #fff;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-danger {
            background-color: #06979A;
        }

        .btn-danger:hover {
            background-color: #0c595aff;
        }

        .btn-1 {
            background-color: #06979A;
            color: white;
            padding: 12px 28px;
            font-weight: bold;
            border-radius: 6px;
            display: inline-block;
            margin-top: 30px;
            text-align: center;
        }

        .btn-1:hover {
            background-color: #0c595aff;
        }

        @media (max-width: 600px) {
            body {
                margin: 10px;
            }

            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead tr {
                display: none;
            }

            tbody tr {
                margin-bottom: 20px;
                background: #fff;
                padding: 15px;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }

            tbody td {
                border: none;
                padding: 10px 10px 10px 50%;
                position: relative;
                text-align: right;
                font-size: 0.9rem;
            }

            tbody td::before {
                position: absolute;
                top: 10px;
                left: 15px;
                width: 45%;
                white-space: nowrap;
                font-weight: 600;
                content: attr(data-label);
                text-align: left;
                color: #555;
            }

            .btn, .btn-danger, .btn-buy, .btn-1 {
                width: 100%;
                margin: 10px 0;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Shopping Cart</h2>

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
        
        <?php if (!empty($cart_items)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    $total = 0; 
                    foreach ($cart_items as $item): 
                        $total += $item['subtotal']; 
                ?>
                <tr data-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>">
                    <td data-label="Name"><?php echo $item['name']; ?></td>
                    <td data-label="Qty">
                        <select class="qty-select" data-id="<?php echo $item['id']; ?>">
                            <?php for ($i = 1; $i <= 99; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo ($item['qty'] == $i) ? 'selected' : ''; ?>>
                                    <?php echo $i; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </td>
                    <td data-label="Price">₹<?php echo number_format($item['price'], 2); ?></td>
                    <td data-label="Subtotal" class="subtotal">₹<?php echo number_format($item['subtotal'], 2); ?></td>
                    <td data-label="Actions">
                        <a href="<?php echo base_url('shop/remove_item/' . $item['id']); ?>" class="btn btn-danger">Remove</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
                            
            <p><strong>Total:</strong> <span id="total-price">₹<?php echo number_format($total, 2); ?></span></p>
            <p><strong>Final Total:</strong> <span id="final-total">₹<?php echo number_format($total, 2); ?></span></p>
                            
            <form method="post" action="<?php echo base_url('shop/buy') ?>" style="margin-top: 30px;">
                <a href="<?php echo base_url('checkout'); ?>" class="btn btn-1" style="background-color: #06979A; margin-right: 15px;">
                    Proceed to Checkout
                </a>
            </form>
                            
        <?php else: ?>
            <p>Your cart is empty.</p>
            <a href="<?php echo base_url('user/home'); ?>" class="btn-1">Go to Shop</a>
        <?php endif; ?>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function updateTotal() {
                let total = 0;
                document.querySelectorAll('tr[data-id]').forEach(row => {
                    const qty = parseInt(row.querySelector('.qty-select').value) || 0;
                    const price = parseFloat(row.getAttribute('data-price')) || 0;
                    const subtotal = qty * price;
                    row.querySelector('.subtotal').textContent = '₹' + subtotal.toFixed(2);
                    total += subtotal;
                });
            
                document.querySelector('#total-price').textContent = '₹' + total.toFixed(2);
                document.querySelector('#final-total').textContent = '₹' + total.toFixed(2);
            }
        
            function updateCartCount() {
                fetch("<?php echo base_url('shop/get_cart_count'); ?>")
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('cart-count-badge');
                    if (data.count && data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'inline-block';
                    } else {
                        badge.style.display = 'none';
                    }
                })
                .catch(err => {
                    console.error('Failed to fetch cart count:', err);
                });
            }
        
            document.querySelectorAll('.qty-select').forEach(select => {
                select.addEventListener('change', function () {
                    const itemId = this.getAttribute('data-id');
                    const qty = this.value;
                
                    fetch("<?php echo base_url('shop/update_cart_ajax'); ?>", {
                        method: "POST",
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `item_id=${itemId}&qty=${qty}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateTotal();
                            updateCartCount();
                        } else {
                            alert("Failed to update cart: " + (data.error || ""));
                        }
                    })
                    .catch(error => {
                        console.error("AJAX error:", error);
                        alert("An error occurred.");
                    });
                });
            });
        
            updateTotal();
            updateCartCount();
        });
    </script>
</body>
</html>
<?php $this->load->view("user/footer"); ?>