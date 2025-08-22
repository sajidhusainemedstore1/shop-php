<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Footer Example</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    footer {
      background: #fff;
      padding: 40px 20px 0;
      border-top: 1px solid #06979A;
    }

    .footer-container {
      display: flex;
      justify-content: space-between;
      max-width: 1100px;
      margin: auto;
      flex-wrap: wrap;
    }

    .footer-column {
      flex: 1;
      min-width: 250px;
      margin: 10px;
    }

    .footer-column h4 {
      font-size: 16px;
      margin-bottom: 10px;
      font-weight: bold;
      position: relative;
    }

    .footer-column h4::after {
      content: "";
      display: block;
      width: 40px;
      height: 2px;
      background: #06a29a;
      margin-top: 5px;
    }

    .footer-column ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .footer-column ul li {
      margin: 8px 0;
    }

    .footer-column ul li a {
      text-decoration: none;
      color: #333;
      font-size: 14px;
    }

    .footer-column ul li a:hover {
      color: #06a29a;
    }

    .footer-contact p {
      font-size: 14px;
      margin: 8px 0;
      color: #333;
    }

    .footer-bottom {
      background: #06979A;
      color: #fff;
      text-align: center;
      padding: 12px 0;
      margin: 0;
      width: 100%;
      left: 0;
      right: 0;
    }
  </style>
</head>
<body>

  <footer>
    <div class="footer-container">
      <div class="footer-column">
        <h4>POLICY INFO</h4>
        <ul>
          <li><a href="<?php echo base_url('user/aboutus')  ?>">About Us</a></li>
          <li><a href="<?php echo base_url('#')  ?>">Privacy Policy</a></li>
          <li><a href="<?php echo base_url('#')  ?>">Terms & Conditions</a></li>
          <li><a href="<?php echo base_url('#')  ?>">Return Policy</a></li>
        </ul>
      </div>

      <div class="footer-column">
        <h4>QUICK LINKS</h4>
        <ul>
          <li><a href="<?php echo base_url('user/my_orders')  ?>">My Orders</a></li>
          <li><a href="<?php echo base_url('user/send')  ?>">Contact Us</a></li>
          <li><a href="<?php echo base_url('#')  ?>">FAQ's</a></li>
        </ul>
      </div>

      <div class="footer-column footer-contact">
        <h4>CONTACT US</h4>
        <p>🏠 2833+MCG, Rabari Colony, Kakoshi, Gujarat 384290</p>
        <p>📞 916985632056</p>
        <p>✉️ admin.shopstore@gmail.com</p>
      </div>
    </div>
  </footer>

  <div class="footer-bottom">
    Copyright © <?php echo date("Y"); ?> <b>Shop</b>. All rights reserved.
  </div>

</body>
</html>
