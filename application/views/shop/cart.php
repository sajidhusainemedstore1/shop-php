<?php $this->load->view("user/header"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .cart-container {
            max-width: 1000px;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        h2 {
            margin-bottom: 20px;
            color: #06979A;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        table th, table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }

        table th {
            background: #f1f3f5;
            color: #444;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        table td {
            font-size: 15px;
            color: #555;
        }

        .qty-select {
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            outline: none;
            font-size: 14px;
        }

        .subtotal {
            font-weight: bold;
            color: #06979A;
        }

        .btn {
            display: inline-block;
            padding: 8px 14px;
            font-size: 14px;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-danger {
            background: #dc3545;
            color: #fff;
            border: none;
        }
        .btn-danger:hover {
            background: #c82333;
        }

        .btn-1 {
            background: #06979A;
            color: #fff;
            border: none;
        }
        .btn-1:hover {
            background: #0b7779ff;
        }

        .checkout-actions {
            text-align: right;
            margin-top: 20px;
        }

        p strong {
            color: #333;
        }

        .alert {
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-size: 14px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container cart-container">
        <h2>Shopping Cart</h2>
        
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($cart_items)): ?>
            <table>
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Qty</th>
                        <th scope="col">Price</th>
                        <th scope="col">Subtotal</th>
                        <th scope="col">Actions</th>
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
                        <label for="qty-<?php echo $item['id']; ?>" class="sr-only">Quantity</label>
                        <select id="qty-<?php echo $item['id']; ?>" class="qty-select" data-id="<?php echo $item['id']; ?>">
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
                        <a href="<?php echo base_url('shop/remove_item/' . $item['id']); ?>" 
                           class="btn btn-danger" aria-label="Remove <?php echo $item['name']; ?>">Remove</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
                            
            <p><strong>Total:</strong> <span id="total-price">₹<?php echo number_format($total, 2); ?></span></p>
            <p><strong>Final Total:</strong> <span id="final-total">₹<?php echo number_format($total, 2); ?></span></p>
                            
            <div class="checkout-actions">
                <a href="<?php echo base_url('checkout'); ?>" class="btn btn-1">
                    Proceed to Checkout
                </a>
            </div>
                            
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
                fetch("<?php echo base_url('get_cart_count'); ?>")
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
