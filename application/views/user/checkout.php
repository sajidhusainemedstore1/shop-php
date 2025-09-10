<?php $this->load->view("user/header"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Checkout securely on MyShop. Choose payment method, apply coupons, and place your order.">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout</title>
  <style>
    .container {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 30px;
    }
    h2 { text-align: center; margin-bottom: 30px; }

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
    .card h3 { margin-bottom: 15px; font-size: 18px; }

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
    small { color: #666; display: block; padding-left: 25px; }

    .add-new-box {
      border: 2px dashed #06979A;
      padding: 30px;
      text-align: center;
      border-radius: 8px;
      cursor: pointer;
      color: #06979A;
      font-weight: bold;
      background-color: #e3f2f3;
    }

    .address-card {
      border: 2px solid #06979A;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 15px;
      background-color: #e3f2f3;
      position: relative;
    }
    .address-card input { margin-right: 10px; }
    .address-default {
      position: absolute;
      right: 15px;
      top: 15px;
      background: #06979A;
      color: white;
      font-size: 12px;
      padding: 2px 8px;
      border-radius: 4px;
    }
    .address-actions { margin-top: 10px; }
    .address-actions a {
      color: #06979A;
      font-weight: bold;
      margin-right: 10px;
      text-decoration: none;
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
    .summary-line strong { font-size: 16px; }

    .wallet-box {
      background: #e3f2f3;
      padding: 12px;
      border-radius: 6px;
      color: #155724;
      font-size: 14px;
      margin-top: 10px;
      border-left: 4px solid #06979A;
    }

    .btn {
      background: #06979A;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }

    .delivery-option { margin-top: 20px; }
    .checkbox-wallet { margin-top: 10px; }

    .btn-preview {
        display: inline-block;
        padding: 10px 20px;
        margin-top: 20px;
        background-color: #06979A;
        color: #fff;
        border: none;
        border-radius: 6px;
        text-decoration: none;
        font-size: 16px;
    }
    .btn-preview:hover { background-color: #117a8b; }

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
    img.prescription-thumb {
      width: 150px;
      border-radius: 8px;
      margin-top: 10px;
    }
  </style>
</head>
<body>

<?php
// Coupon / wallet calculation
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

// Prescription selected from session
$selected_prescription_id = $this->session->userdata('selected_prescription');
$selected_prescription = null;
if ($selected_prescription_id) {
  $selected_prescription = $this->prescription_model->get($selected_prescription_id);
}
?>

<h2>CHECKOUT</h2>
<?php if ($this->session->flashdata('success')): ?>
  <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
  <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<div class="checkout-grid">
  <div>
    <!-- Prescription section -->
    <div class="card">
      <h3>Item in your cart requires a Prescription</h3>
      <div class="radio-box">
        <label for="presc-no">
          <input type="radio" id="presc-no" name="prescription" value="no" checked>
          I don't have a prescription
          <small>Doctors will consult you without charges for your order.</small>
        </label>
        <label for="presc-yes">
          <input type="radio" id="presc-yes" name="prescription" value="yes">
          I have a prescription
          <small>Our pharmacist will dispense medicines only if the prescription is valid & meets all government regulations.</small>
        </label>
      </div>

      <div id="prescription-section" style="display:none;">
        <div class="add-new-box" onclick="window.location.href='<?php echo base_url('user/prescription_upload'); ?>'">
          + Select Prescription
        </div>
        <?php if(!empty($selected_prescription)): ?>
            <div style="text-align:center;">
                <img src="<?php echo base_url('uploads/'.$selected_prescription['file']); ?>" 
                     alt="Prescription" class="prescription-thumb">
                <br>
                <a href="<?php echo base_url('user/remove_prescription'); ?>" style="color:red;font-weight:bold;text-decoration:none;">Remove</a>
            </div>
        <?php else: ?>
            <p style="color:red;text-align:center;">No Prescription Selected</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Address section -->
    <div class="card">
      <h3>Select Delivery Address</h3>
      <?php if (!empty($addresses)): ?>
        <?php foreach ($addresses as $addr): ?>
          <div class="address-card">
            <label>
              <input type="radio" name="address_id" value="<?php echo $addr['id']; ?>" <?php echo $addr['is_default'] ? 'checked' : ''; ?>>
              <strong>Mobile:</strong> <?php echo htmlspecialchars($addr['mobile']); ?><br>
              <strong>Email:</strong> <?php echo htmlspecialchars($addr['email']); ?><br>
              <strong>Address:</strong> <?php echo nl2br(htmlspecialchars($addr['address'])); ?>,
              <?php echo htmlspecialchars($addr['city']); ?>, <?php echo htmlspecialchars($addr['state']); ?> -
              <?php echo htmlspecialchars($addr['pincode']); ?>
            </label>
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
      <form action="<?php echo base_url('checkout/apply_coupon'); ?>" method="post" autocomplete="off">
        <input type="text" id="coupon_code" name="coupon_code" placeholder="Enter Promocode" style="width: 90%; padding: 8px; border-radius: 5px;">
        <br><br>
        <button type="submit" class="btn">Apply Promocode</button>
      </form>
      <?php if (!empty($coupon)): ?>
        <form action="<?php echo base_url('checkout/remove_coupon'); ?>" method="post">
          <button type="submit" class="btn" style="margin-top: 10px;background:#dc3545;">Remove Coupon</button>
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
          You can save up to <?php echo number_format($wallet_percentage, 1) ?>% of your cart value using wallet cash.
        </div>
      <?php endif; ?>
    </div>

    <div class="card delivery-option">
      <h3>Delivery Type</h3>
      <label for="delivery-home">
        <input type="radio" id="delivery-home" name="delivery_type" value="home" checked> Home Delivery
      </label>
    </div>

    <div class="summary-section">
      <form action="<?php echo base_url('checkout/preview_order'); ?>" method="post" autocomplete="off">
        <input type="hidden" name="prescription" value="">
        <input type="hidden" name="delivery_type" value="">
        <input type="hidden" name="use_wallet" value="<?php echo $this->session->userdata('use_wallet') ? 1 : 0; ?>">
        <button type="submit" class="btn-preview">Preview Order</button>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const prescYes = document.getElementById("presc-yes");
  const prescNo = document.getElementById("presc-no");
  const prescSection = document.getElementById("prescription-section");
  function togglePresc() {
    if (prescYes.checked) prescSection.style.display = "block";
    else prescSection.style.display = "none";
  }
  prescYes.addEventListener("change", togglePresc);
  prescNo.addEventListener("change", togglePresc);
  togglePresc();

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
      fetch("<?php echo base_url('checkout/confirm_preview') ?>", {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'use_wallet=' + status
      });
    }
  }

  document.querySelector("form[action$='preview_order']").addEventListener("submit", function(e) {
    let presc = document.querySelector("input[name='prescription']:checked");
    this.querySelector("input[name='prescription']").value = presc ? presc.value : "";

    let del = document.querySelector("input[name='delivery_type']:checked");
    this.querySelector("input[name='delivery_type']").value = del ? del.value : "";

    let addr = document.querySelector("input[name='address_id']:checked");
    if (addr) {
      let hiddenAddr = document.createElement("input");
      hiddenAddr.type = "hidden";
      hiddenAddr.name = "address_id";
      hiddenAddr.value = addr.value;
      this.appendChild(hiddenAddr);
    }
  });
});
</script>
</body>
</html>
<?php $this->load->view("user/footer"); ?>
