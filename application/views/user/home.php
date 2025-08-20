<?php $this->load->view("user/header"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Home - Products</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .alert {
            width: 100%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 15px 20px;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
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

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .products {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .product-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 10px;
            width: calc(25% - 20px);
            box-sizing: border-box;
        }

        .product-card img {
            width: 100%;
            height: 190px;
            border-radius: 6px;
            object-fit: cover;
        }

        .product-card h3 {
            margin: 10px 0 5px;
        }

        .product-card p {
            color: #555;
            margin: 5px 0;
        }

        .product-card .price {
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0;
            color: #06979A;
        }

        .product-card form {
            text-align: center;
        }

        .product-card button {
            background-color: #06979A;
            border: none;
            color: white;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .product-card button:hover {
            background-color: rgb(29, 151, 160);
        }

        @media (max-width: 992px) {
            .product-card {
                width: calc(33.333% - 20px);
            }
        }

        @media (max-width: 768px) {
            .product-card {
                width: calc(50% - 20px);
            }
        }

        @media (max-width: 480px) {
            .product-card {
                width: 100%;
            }
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -10px;
            background: red;
            color: white;
            font-size: 12px;
            padding: 2px 6px;
            border-radius: 50%;
            font-weight: bold;
        }

        .nav-link {
            display: inline-block;
            text-decoration: none;
            color: #06979A;
            font-weight: bold;
            margin-left: 15px;
            padding: 6px 12px;
            border: 1px solid #06979A;
            border-radius: 4px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
    
        .nav-link:hover {
            background-color: #06979A;
            color: white;
        }
    
        .top-nav {
            background-color: #fff;
            border-bottom: 1px solid #ddd;
            padding: 10px 20px;
            text-align: right;
            max-width: 1200px;
            margin: 0 auto;
        }
    
        .top-nav .nav-link {
            text-decoration: none;
            color: #06979A;
            font-weight: bold;
            margin-left: 15px;
            padding: 6px 12px;
            border: 1px solid #06979A;
            border-radius: 4px;
            transition: background 0.3s, color 0.3s;
        }
    
        .top-nav .nav-link:hover {
            background-color: #06979A;
            color: white;
        }
    
        .top-nav .cart-icon {
            position: relative;
            font-size: 18px;
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

    <?php
        $user_id = $this->session->userdata('user_id');
        $user_logged_in = $this->session->userdata('user_logged_in');
        $cart_count = $this->cart_model->count_items($user_id);
    ?>

    

    <div>
        <nav class="top-nav">
            <?php if ($user_logged_in): ?>
                <a href="<?php echo base_url('user/logout'); ?>" class="nav-link">Logout</a>
                <a href="<?php echo base_url('user/view/' . $user_id) ?>" class="nav-link">Wallet History</a>
                <a href="<?php echo base_url('user/my_orders'); ?>" class="nav-link">My Orders</a>
                <a href="<?php echo base_url('shop/cart'); ?>" class="nav-link cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <span id="cart-count-badge" class="cart-badge"><?php echo $cart_count; ?></span>
                </a>
            <?php else: ?>
                <a href="<?php echo base_url('user/login'); ?>" class="nav-link">Login</a>
            <?php endif; ?>
        </nav>
    </div>
    <div class="container">
        <div class="products">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="<?php echo base_url('uploads/' . $product['image']); ?>" alt="Product Image">
                    <h3><?php echo $product['name']; ?></h3>
                    <p><?php echo $product['description']; ?></p>
                    <p class="price">₹<?php echo number_format($product['price'], 2); ?></p>
                    <form class="add-to-cart-form" method="post" action="<?php echo base_url('shop/add_to_cart'); ?>">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.add-to-cart-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                
                    fetch(this.action, {
                        method: 'POST',
                        body: new URLSearchParams(formData),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        const badge = document.getElementById('cart-count-badge');
                        if (data.cart_count !== undefined) {
                            badge.textContent = data.cart_count;
                            badge.style.display = 'inline-block';
                        }
                    
                        const msg = document.createElement('div');
                        msg.className = 'alert ' + (data.success ? 'alert-success' : 'alert-danger');
                        msg.textContent = data.message || data.error || 'Unexpected response';
                        document.body.insertBefore(msg, document.body.firstChild);
                    
                        setTimeout(() => msg.remove(), 3000);
                    })
                    .catch(err => {
                        console.error('AJAX error:', err);
                        alert('Something went wrong. Try logging in again.');
                    });
                });
            });
        });
    </script>

</body>
</html>
<?php $this->load->view("user/footer"); ?>