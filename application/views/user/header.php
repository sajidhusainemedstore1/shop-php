<?php
    $user_id = $this->session->userdata('user_id');
    $user_logged_in = $this->session->userdata('user_logged_in');
    $cart_count = $this->cart_model->count_items($user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Medical Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { margin: 0; font-family: Arial, sans-serif; }

        /* Top small bar */
        .top-bar {
            background: #06979A;
            color: #fff;
            font-size: 14px;
            padding: 6px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .top-bar a {
            color: #fff;
            margin-left: 15px;
            text-decoration: none;
            font-weight: bold;
        }

        /* Account popup */
        .account-container {
            position: relative;
            display: inline-block;
        }
        .account-btn {
            background: none;
            border: none;
            color: #fff;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .account-popup {
            display: none;
            position: absolute;
            top: 30px;
            right: 0;
            background: #fff;
            min-width: 200px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            border-radius: 6px;
            z-index: 999;
            animation: fadeIn 0.2s ease;
        }
        .account-popup::before {
            content: "";
            position: absolute;
            top: -8px;
            right: 15px;
            border-width: 0 8px 8px 8px;
            border-style: solid;
            border-color: transparent transparent #fff transparent;
        }
        .popup-content a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #333;
            font-size: 14px;
        }
        .popup-content a:hover {
            background: #f5f5f5;
        }
        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(-5px);}
            to {opacity: 1; transform: translateY(0);}
        }

        /* Main header */
        .main-header {
            background: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 20px;
            border-bottom: 1px solid #ddd;
        }
        .logo-area img {
            height: 70px;
            width: auto;
        }

        /* Menu */
        .menu {
            display: flex;
            gap: 25px;
        }
        .menu a {
            color: #000;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .menu a i {
            color: #06979A;
        }

        /* Search */
        .search-box {
            flex: 1;
            display: flex;
            margin: 0 30px;
        }
        .search-box input {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px 0 0 4px;
            max-width : 70%;
        }
        .search-box button {
            background: #06979A;
            border: none;
            padding: 8px 14px;
            color: #fff;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }

        /* Right side actions */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .cart-icon {
            position: relative;
            font-size: 20px;
            color: #06979A;
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
        .upload-btn {
            background: #06979A;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }
        .upload-btn:hover {
            background: #047b7d;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <span>Customer Support: <strong>919429691060</strong></span>
    <div>
        <a href="">OFFERS</a>
        <?php if ($user_logged_in): ?>
            <div class="account-container">
                <button class="account-btn" onclick="togglePopup()">
                    <i class="fa fa-user"></i> My Account <i class="fa fa-caret-down"></i>
                </button>
                <div id="accountPopup" class="account-popup">
                    <div class="popup-content">
                <a href="<?php echo base_url('user/view/' . $user_id) ?>" class="nav-link">💳 Wallet History</a>
                        <a href="<?php echo base_url('user/my_orders'); ?>">📦 My Orders</a>
                        <a href="<?php echo base_url('user/logout'); ?>">🚪 Logout</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <a href="<?php echo base_url('user/login'); ?>">
                <i class="fa fa-user"></i> Sign in / Sign up
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="main-header">
    <div class="logo-area">
        <a href="<?php echo base_url(); ?>">
            <img src="<?php echo base_url('assets/images/medical-logo.png'); ?>" alt="Logo">
        </a>
    </div>

    <!-- <div class="menu">
        <a href=""><i class="fas fa-pills"></i> Medicines</a>
        <a href=""><i class="fas fa-vials"></i> Lab Tests</a>
        <a href=""><i class="fas fa-user-md"></i> Find Doctors</a>
    </div> -->

    <div class="search-box">
        <input type="search" id="searchInput" onkeyup="filterTable()">
        <button onclick="filterTable()"><i class="fas fa-search"></i></button>
    </div>

    <div class="header-actions">
        <?php if ($user_logged_in): ?>
            <a href="<?php echo base_url('shop/cart'); ?>" class="nav-link cart-icon">
                <i class="fas fa-shopping-cart"></i>
                <span id="cart-count-badge" class="cart-badge"><?php echo $cart_count; ?></span>
            </a>
        <?php else: ?>
            <a href="<?php echo base_url('user/home'); ?>" class="nav-link cart-icon">
                <i class="fas fa-shopping-cart"></i>
                <span id="cart-count-badge" class="cart-badge">0</span>
            </a>
        <?php endif; ?>

        <a href="" class="upload-btn">Upload RX</a>
    </div>
</div>

<div id="loader" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
 background:rgba(255,255,255,0.8); z-index:9999; display:flex; align-items:center; justify-content:center;">
   <img src="https://i.gifer.com/ZZ5H.gif" alt="Loading..." width="80">
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function togglePopup() {
        const popup = document.getElementById("accountPopup");
        popup.style.display = (popup.style.display === "block") ? "none" : "block";
    }
    
    window.onclick = function(event) {
        if (!event.target.closest(".account-container")) {
            document.getElementById("accountPopup").style.display = "none";
        }
    }

    function filterProducts() {
        const input = document.getElementById("searchInput").value.toLowerCase();
        const products = document.querySelectorAll(".product-card");
        
        if (input.length < 3) {
            products.forEach(p => p.style.display = "");
            return;
        }
    
        products.forEach(p => {
            const text = p.innerText.toLowerCase();
            p.style.display = text.includes(input) ? "" : "none";
        });
    }
    
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("searchInput");
        if (searchInput) {
            searchInput.addEventListener("keyup", filterProducts);
        }
    });
</script>
