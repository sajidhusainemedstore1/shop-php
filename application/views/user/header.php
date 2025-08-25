<?php
    // These lines are crucial. You need to get the user data and cart count
    // in the header file itself, as it's a separate view.
    $user_id = $this->session->userdata('user_id');
    $user_logged_in = $this->session->userdata('user_logged_in');
    // Ensure you have a 'cart_model' and a 'count_items' function.
    $cart_count = $this->cart_model->count_items($user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #06979A;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-left h4 {
            margin: 0;
            font-size: 16px;
        }
        
        .header-center h3 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }

        .header-right {
            display: flex;
            align-items: center;
        }
        
        .nav-link {
            display: inline-block;
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            margin-left: 15px;
            padding: 6px 12px;
            border: 1px solid #fff;
            border-radius: 4px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .nav-link:hover {
            background-color: #fff;
            color: #06979A;
        }
        
        .cart-icon {
            position: relative;
            font-size: 18px;
            margin-left: 15px;
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

    </style>
</head>
<body>
    <header>
        <div class="header-left">
            <h4>Customer Support: 6985632056</h4>
        </div>
        <div class="header-center">
            <h3>Welcome to Our Store</h3>
        </div>
        <div class="header-right">
            <?php if ($user_logged_in): ?>
                <a href="<?php echo base_url('user/logout'); ?>" class="nav-link">Logout</a>
                <a href="<?php echo base_url('user/view/' . $user_id) ?>" class="nav-link">Wallet History</a>
                <a href="<?php echo base_url('user/my_orders'); ?>" class="nav-link">My Orders</a>
                <a href="<?php echo base_url('shop/cart'); ?>" class="nav-link cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <span id="cart-count-badge" class="cart-badge"><?php echo $cart_count; ?></span>
                </a>
                <a class="nav-link" ><h3><?php echo $user['fullname']; ?></h3></a>
            <?php else: ?>
                <a href="<?php echo base_url('user/login'); ?>" class="nav-link">Sign in / Sign up</a>
            <?php endif; ?>
        </div>
    </header>