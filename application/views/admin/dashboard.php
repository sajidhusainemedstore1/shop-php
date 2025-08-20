<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
    }

    .admin-dashboard {
        display: flex;
        min-height: 100vh;
    }

    .sidebar {
        width: 220px;
        background-color: #1f4a41;
        color: white;
        padding: 20px;
    }

    .sidebar h2 {
        font-size: 24px;
        margin-bottom: 20px;
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
        transition: color 0.3s;
    }

    .sidebar nav ul li a:hover {
        color:rgb(219, 31, 31);
    }

    .main-content {
        flex: 1;
        padding: 30px;
        background-color: #ecf0f1;
    }

    header h1 {
        font-size: 28px;
        margin-bottom: 10px;
    }

    header p {
        font-size: 16px;
        color: #555;
        margin-bottom: 30px;
    }

    .quick-links {
        background-color: white;
        padding: 20px;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .quick-links h2 {
        font-size: 22px;
        margin-bottom: 15px;
    }

    .quick-links ul {
        list-style: none;
        padding-left: 0;
    }

    .quick-links ul li {
        margin-bottom: 10px;
    }

    .quick-links ul li a {
        color: #3498db;
        text-decoration: none;
        font-weight: 500;
    }

    .quick-links ul li a:hover {
        text-decoration: underline;
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
            <header>
                <h1>Welcome to the Admin Dashboard</h1>
                <p>Total Users: <?php echo $total_users ?> | Total Products: <?php echo $total_products ?> | Total Coupons: <?php echo $total_coupons?></p>  
            </header>

            <section class="quick-links">
                <h2>Quick Actions</h2>
                <ul>
                    <li><a href="<?php echo base_url('admin/signup') ?>">Add New User</a></li>
                    <li><a href="<?php echo base_url('admin/add_product') ?>">Add New Product</a></li>
                    <li><a href="<?php echo base_url('admin/add_coupon'); ?>">Add New Coupon</a></li>
                </ul>
            </section>
        </main>
    </div>
</body>
</html>
