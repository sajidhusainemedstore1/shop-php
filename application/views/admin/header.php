<!DOCTYPE html>
<html>
<head>
    <title><?php echo isset($page_title) ? $page_title : 'Admin Dashboard' ?></title>
<style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    min-height: 100vh;
}

.admin-dashboard {
    display: flex;
    min-height: 100vh;
}

/* Sidebar */
.sidebar {
    width: 220px;
    background-color: #06979A;
    color: white;
    padding: 20px;
    flex-shrink: 0;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    overflow-y: auto;
}

.sidebar h2 {
    font-size: 24px;
    margin-bottom: 20px;
    text-align: center;
}

.sidebar nav ul {
    list-style: none;
}

.sidebar nav ul li {
    margin: 15px 0;
}

.sidebar nav ul li a {
    color: white;
    text-decoration: none;
    font-weight: bold;
    display: block;
    padding: 8px 12px;
    border-radius: 4px;
    transition: background 0.3s;
}

.sidebar nav ul li a:hover,
.sidebar nav ul li a.active {
    background-color: #07434bff;
    color: #fff;
}

/* Main Content */
.main-content {
    margin-left: 220px;
    padding: 30px;
    background-color: #ecf0f1;
    flex-grow: 1;
    min-height: 100vh;
}

/* Top Header (optional, if needed separately) */
.top-header {
    background-color: #06979A;
    color: white;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 999;
}

/* Footer */
footer {
    text-align: center;
    padding: 10px;
    background: #06979a;
    border-top: 1px solid #06979a;
    margin-top: 30px;
}

.sidebar h2 {
    font-size: 24px;
    margin-bottom: 20px;
    /* text-align: center; */
    color: white;
}

</style>
</head>
<body>
<div class="admin-dashboard">
    <aside class="sidebar">
        <h2>Admin Panel</h2>
        <nav>
            <ul>
                <li><a href="<?php echo base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li><a href="<?php echo base_url('admin/userlist') ?>">Users</a></li>
                <li><a href="<?php echo base_url('admin/product_list') ?>">Products</a></li>
                <li><a href="<?php echo base_url('wallet/wallet_dashboard') ?>">Wallet</a></li>
                <li><a href="<?php echo base_url('admin/coupons') ?>">Promocode</a></li>
                <li><a href="<?php echo base_url('admin/order_list') ?>">Customer Orders</a></li>
                <li><a href="<?php echo base_url('admin/logout') ?>">Logout</a></li>
            </ul>
        </nav>
    </aside>
    <main class="main-content">
        <div id="loader" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.8); z-index:9999; display:flex; align-items:center; justify-content:center;">
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
