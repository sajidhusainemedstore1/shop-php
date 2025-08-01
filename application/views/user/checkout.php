<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 30px;
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
    }

    .checkout-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 30px;
      max-width: 1200px;
      margin: auto;
    }

    .card {
      background: white;
      border-radius: 8px;
      box-shadow: 0 0 10px #e0e0e0;
      padding: 20px;
      margin-bottom: 20px;
    }

    .card h3 {
      margin-bottom: 15px;
      font-size: 18px;
    }

    .radio-box {
      border: 1px solid #ccc;
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 15px;
    }

    .radio-box label {
      display: block;
      margin-bottom: 10px;
      cursor: pointer;
    }

    small {
      color: #666;
      display: block;
      padding-left: 25px;
    }

    .address-card {
      border: 2px solid #28a745;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 15px;
      background-color: #f7fff8;
      position: relative;
    }

    .address-card input {
      margin-right: 10px;
    }

    .address-default {
      position: absolute;
      right: 15px;
      top: 15px;
      background: #28a745;
      color: white;
      font-size: 12px;
      padding: 2px 8px;
      border-radius: 4px;
    }

    .address-actions {
      margin-top: 10px;
    }

    .address-actions a {
      color: #28a745;
      font-weight: bold;
      margin-right: 10px;
      text-decoration: none;
    }

    .add-new-box {
      border: 2px dashed #ccc;
      padding: 30px;
      text-align: center;
      border-radius: 8px;
      cursor: pointer;
      color: #999;
      font-weight: bold;
    }

    .summary-section {
      background: #fff;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 0 10px #e0e0e0;
    }

    .summary-line {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
      font-size: 15px;
    }

    .summary-line strong {
      font-size: 16px;
    }

    .wallet-box {
      background: #eafaf1;
      padding: 12px;
      border-radius: 6px;
      color: #155724;
      font-size: 14px;
      margin-top: 10px;
      border-left: 4px solid #28a745;
    }

    .btn {
      background: #007bff;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }

    .delivery-option {
      margin-top: 20px;
    }

    .checkbox-wallet {
      margin-top: 10px;
    }

    .btn-preview {
      margin-top: 20px;
      width: 50%;
    }

    .alert {
      padding: 15px 20px;
      margin-bottom: 20px;
      border-radius: 5px;
      width: 100%;
      max-width: 700px;
      margin-left: auto;
      margin-right: auto;
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

    .btn-danger {
      background-color: #dc3545;
    }

    .btn-danger:hover {
      background-color: #b02a37;
    }

    .btn-preview {
        display: inline-block;
        padding: 10px 20px;
        margin-top: 20px;
        background-color: #17a2b8;
        color: #fff;
        border: none;
        border-radius: 6px;
        text-decoration: none;
        font-size: 16px;
    }

    .btn-preview:hover {
        background-color: #117a8b;
    }

  </style>
</head>
<body>

  <?php
    $coupon = $this->session->userdata('coupon') ?? null;
    $discount = 0;
    $payable_amount = $sub_total;

    if (!empty($coupon)) {
        $discount = $coupon['calculated_discount'] ?? 0;
        $payable_amount -= $discount;
    }

    $wallet_use_amount = 0;
    $wallet_percentage = 0;

    if (!empty($user['wallet_balance']) && $user['wallet_balance'] > 0) {
        $wallet_setting = $this->db->where('name', 'Wallet Percentage')->get('settings')->row();
        if ($wallet_setting) {
            $wallet_percentage = (float)$wallet_setting->value;
        }
        $wallet_base = $sub_total - $discount;
        $wallet_use_amount = round(($wallet_percentage / 100) * $wallet_base, 2);
        $wallet_use_amount = min($wallet_use_amount, $user['wallet_balance']);

    }
    $data['wallet_use_amount'] = $wallet_use_amount;
  ?>

  <h2>CHECKOUT</h2>
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

  <div class="checkout-grid">
    <div>
      <div class="card">
        <h3>Item in your cart required Prescription</h3>
        <div class="radio-box">
          <label>
            <input type="radio" name="prescription" value="no" checked>
            I don't have a prescription
            <small> doctors will consult you without charges for your order.</small>
          </label>
          <label>
            <input type="radio" name="prescription" value="yes">
            I have prescription
            <small>Our pharmacist will dispense medicines only if the prescription is valid & it meets all government regulations.</small>
          </label>
        </div>
      </div>

      <div class="card">
        <h3>Select Delivery Address</h3>
        <?php if (!empty($addresses)): ?>
          <?php foreach ($addresses as $addr): ?>
            <div class="address-card">
              <input type="radio" name="address_id" value="<?php echo $addr['id']; ?>" <?php echo $addr['is_default'] ? 'checked' : ''; ?>><br>
              <strong>Mobile:</strong> <?php echo htmlspecialchars($addr['mobile']); ?><br>
              <strong>Email:</strong> <?php echo htmlspecialchars($addr['email']); ?><br>
              <strong>Address:</strong> <?php echo nl2br(htmlspecialchars($addr['address'])); ?>,
              <?php echo htmlspecialchars($addr['city']); ?>, <?php echo htmlspecialchars($addr['state']); ?> -
              <?php echo htmlspecialchars($addr['pincode']); ?>
              <?php if ($addr['is_default']): ?>
                <div class="address-default">Default</div>
              <?php endif; ?>
              <div class="address-actions">
                <a href="<?php echo base_url('user/edit_address/' . $addr['id']); ?>">Edit</a>
                <a href="<?php echo base_url('user/delete_address/' . $addr['id']); ?>" onclick="return confirm('Are you sure?')">Remove</a>
              </div>
            </div>
          <?php endforeach; ?>
          <div class="add-new-box" onclick="window.location.href='<?php echo base_url('user/add_address'); ?>'">+ Add new address</div>
        <?php else: ?>
          <p>No delivery address found.</p>
          <div class="add-new-box" onclick="window.location.href='<?php echo base_url('user/add_address'); ?>'">+ Add Address</div>
        <?php endif; ?>
      </div>
    </div>

    <div>
      <div class="summary-section">
        <h3>Apply Coupons</h3>
        <form action="<?php echo base_url('checkout/apply_coupon'); ?>" method="post">
          <input type="text" name="coupon_code" placeholder="Enter Promocode" style="width: 90%; padding: 8px; border-radius: 5px;">
          <br><br>
          <button type="submit" class="btn">Apply Promocode</button>
        </form>
        <?php if (!empty($coupon)): ?>
          <form action="<?php echo base_url('checkout/remove_coupon'); ?>" method="post">
            <button type="submit" class="btn btn-danger" style="margin-top: 10px;">Remove Coupon</button>
          </form>
        <?php endif; ?>
      </div><br>

      <div class="summary-section">
        <h3>Shopping Cart</h3>

        <div class="summary-line">
          <span>Total</span>
          <span>Rs <?php echo number_format($sub_total, 2); ?></span>
        </div>

        <?php if (!empty($coupon)): ?>
          <div class="summary-line">
            <span>Coupon (<?php echo htmlspecialchars($coupon['code']); ?>)</span>
            <span>- Rs <?php echo number_format($discount, 2); ?></span>
          </div>
        <?php endif; ?>
        
        <?php if (!empty($user['wallet_balance']) && $wallet_use_amount > 0): ?>
          <div id="wallet-balance-row" class="summary-line" style="<?php echo $this->session->userdata('use_wallet') ? 'display: flex;' : 'display: none;'; ?>">
            <span>Wallet Balance</span>
            <span>- Rs <?php echo number_format(min($wallet_use_amount, $user['wallet_balance']), 2); ?></span>
          </div>
        <?php endif; ?>
        
        <div class="summary-line total-final">
          <strong>Total Amount</strong>
          <strong id="payable-amount">Rs <?php echo number_format($payable_amount, 2); ?></strong>
        </div>
        
        <?php if (!empty($user['wallet_balance']) && $wallet_use_amount > 0): ?>
          <div class="checkbox-wallet">
            <input type="checkbox" id="wallet_checkbox" <?php if ($this->session->userdata('use_wallet')) echo 'checked'; ?>>
            <label for="wallet_checkbox">
              Pay ₹<?php echo number_format($wallet_use_amount, 2) ?> using Wallet
            </label>
          </div>

          <div class="wallet-box">
            You have ₹<?php echo number_format($user['wallet_balance'], 2) ?> in your wallet.
            You can save up to <?php echo number_format($wallet_percentage, 0) ?>% of your cart value using wallet cash.
          </div>
        <?php endif; ?>
      </div>

      <div class="card delivery-option">
        <h3>Delivery Type</h3>
        <label><input type="radio" name="delivery_type" value="home" checked> Home Delivery</label>
      </div>

      <div class="summary-section">
        <a href="<?php echo base_url('checkout/preview_order') ?>" class="btn-preview">Preview Order</a>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const walletCheckbox = document.getElementById("wallet_checkbox"); 
      const finalAmountEl = document.getElementById("payable-amount");
      const walletRow = document.getElementById("wallet-balance-row");
    
      const originalAmount = parseFloat(finalAmountEl.textContent.replace(/[^\d.]/g, ''));
      const walletAmount = <?php echo json_encode($wallet_use_amount); ?>;
    
      if (walletCheckbox) {
        walletCheckbox.addEventListener("change", function () {
          if (this.checked) {
            walletRow.style.display = "flex";
            finalAmountEl.textContent = "Rs " + (originalAmount - walletAmount).toFixed(2);
            updateWalletUsage(1);
          } else {
            walletRow.style.display = "none";
            finalAmountEl.textContent = "Rs " + originalAmount.toFixed(2);
            updateWalletUsage(0);
          }
        });

        function updateWalletUsage(status) {
          $.post("<?php echo base_url('checkout/confirm_preview') ?>", { use_wallet: status });
        }
      }
    });
  </script>
</body>
</html>