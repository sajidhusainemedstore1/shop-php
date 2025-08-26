<?php $this->load->view("admin/header"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>


    header h1 {
        font-size: 28px;
        margin-bottom: 10px;
        text-align: center;
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
        color: #06979A;
        text-decoration: none;
        font-weight: 500;
    }

    .quick-links ul li a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
        <main class="container">
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
<?php $this->load->view("admin/footer"); ?>