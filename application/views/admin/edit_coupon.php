<?php $this->load->view("admin/header"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Edit Coupon</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f7f8;
        padding: 20px;
        margin: 0;
    }

    h2 {
        text-align: center;
        color: #333;
    }

    form {
        max-width: 600px;
        margin: 0 auto;
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    label {
        display: block;
        margin-bottom: 6px;
        font-weight: bold;
        color: #333;
    }

    input[type="text"],
    input[type="number"],
    input[type="date"],
    input[type="time"],
    select,
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-sizing: border-box;
        font-size: 14px;
    }

    textarea {
        min-height: 80px;
        resize: vertical;
    }

    button[type="submit"] {
        background-color: #007bff;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        width: 30%;
        transition: background-color 0.3s ease;
    }

    button[type="submit"]:hover {
        background-color: #0056b3;
    }

    p {
        margin: 10px 0;
        color: red;
    }

    @media (max-width: 600px) {
        form {
            padding: 20px;
        }
    }
</style>
<script src="https://cdn.tiny.cloud/1/abt1uwkzsltbzytpan4rhv5d6xa67u8em1frbgql357yr5cp/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: 'textarea[name="terms"], textarea[name="description"]',
    menubar: false,
    plugins: 'lists link',
    toolbar: 'undo redo | bold italic underline | bullist numlist | link',
    height: 200
  });
</script>
</head>
<body>

    <h2>Edit Coupon</h2>

    <?php echo validation_errors('<p style="color:red;">', '</p>'); ?>

        <form method="post" enctype="multipart/form-data" action="<?php echo base_url('admin/edit_coupon/' . $coupon['code']); ?>">

            <label>Promo Code *</label>
            <input type="text" name="code" pattern="[A-Za-z0-9]+" required value="<?php echo $coupon['code']; ?>" />

            <label>Number of Users</label>
            <input type="number" name="number_of_users" value="<?php echo $coupon['number_of_users']; ?>" />

            <label>Select Redemption Limit *</label>
            <select name="redemption_limit" id="redemption_limit" onchange="toggleCustomField()">
                <option value="unlimited" <?php echo ($coupon['redemption_limit'] == 'unlimited') ? 'selected' : ''; ?>>Unlimited</option>
                <option value="customized" <?php echo ($coupon['redemption_limit'] == 'customized') ? 'selected' : ''; ?>>Customized</option>
            </select>
            
            <div id="custom_limit_field" style="display: none; margin-top: 10px;">
                <label>Number of Redemption Limit *</label>
                <input type="number" name="custom_redemption_limit" value="<?php echo $coupon['custom_redemption_limit']; ?>" min="1">
            </div>

            <label>Description</label>
            <textarea name="description"><?php echo ($coupon['description'] ?? ''); ?></textarea>

            <label>Terms & Conditions</label>
            <textarea name="terms"><?php echo ($coupon['terms_conditions'] ?? ''); ?></textarea>


            <label>Select Customers By</label>
            <select name="customer_filter">
                <option value="all" <?php echo ($coupon['customer_filter'] == 'all') ? 'selected' : ''; ?>>All</option>
                <option value="mobile" <?php echo ($coupon['customer_filter'] == 'mobile') ? 'selected' : ''; ?>>Mobile Number</option>
                <option value="registered_date" <?php echo ($coupon['customer_filter'] == 'registered_date') ? 'selected' : ''; ?>>Registered Date</option>
            </select>

            <label>Discount Option *</label>
            <select name="discount_type" id="discount_type" onchange="toggleDiscountFields()" >
                <option value="percentage" <?php echo (isset($coupon['discount_type']) && $coupon['discount_type'] == 'percentage') ? 'selected' : ''; ?>>Discount (%)</option>
                <option value="fixed" <?php echo (isset($coupon['discount_type']) && $coupon['discount_type'] == 'fixed') ? 'selected' : ''; ?>>Discount Price</option>
            </select>

	        <div id="discount_percentage_fields" style="display:none;">
                <label>Discount Value *</label>
                <input type="text" name="discount_value" value="<?php echo set_value('value', $coupon['value'] ?? ''); ?>" />
                
                <label>Maximum Discount Amount *</label>
                <input type="text" name="max_discount" value="<?php echo set_value('max_discount_amount', $coupon['max_discount_amount'] ?? ''); ?>" />
            </div>
        
            <div id="discount_fixed_fields" style="display:none;">
                <label>Discount Amount *</label>
                <input type="text" name="dis_amount" value="<?php echo set_value('dis_amount', $coupon['dis_amount'] ?? ''); ?>" />
            </div>
        
            <div id="min_purchase_field" style="display:none;">
                <label>Minimum Purchase Amount *</label>
                <input type="text" name="min_purchase" value="<?php echo set_value('min_purchase_amount', $coupon['min_purchase_amount'] ?? ''); ?>" />
            </div>
            
            <?php
                date_default_timezone_set('Asia/Kolkata');
                $today = date('Y-m-d');
                $current_time = date('H:i');
            ?>

            <label>Start Date *</label>
            <input type="date" name="start_date" min="<?php echo $today; ?>" value="<?php echo set_value('start_date', $coupon['start_date'] ?? ''); ?>" required/>

            <label>Start Time *</label>
            <input type="time" name="start_time" value="<?php echo set_value('start_time', $coupon ['start_time'] ?? ''); ?>" required />

            <label>Expiry Date *</label>
            <input type="date" name="expiry_date" min="<?php echo $today; ?>" value="<?php echo set_value('expiry_date'), $coupon ['expiry_date'] ?? ''; ?>" required/>

            <label>Expiry Time *</label>
            <input type="time" name="expiry_time" value="<?php echo set_value('expiry_time', $coupon ['expiry_time'] ?? $current_time); ?>" required />
            
            <button type="submit">Update Promocode</button>

        </form>

</body>
<script>
    function toggleCustomField() {
        var select = document.getElementById("redemption_limit");
        var customField = document.getElementById("custom_limit_field");
        var customInput = customField.querySelector('input[name="custom_redemption_limit"]');
        
        if (select.value === "customized") {
            customField.style.display = "block";
            customInput.disabled = false;
            customInput.required = true;
        } else {
            customField.style.display = "none";
            customInput.disabled = true;
            customInput.required = false;
        }
    }

    function toggleDiscountFields() {
        var discountType = document.getElementById("discount_type").value;

        var percentageFields = document.getElementById("discount_percentage_fields");
        var fixedFields = document.getElementById("discount_fixed_fields");
        var minPurchaseField = document.getElementById("min_purchase_field");

        percentageFields.style.display = "none";
        fixedFields.style.display = "none";
        minPurchaseField.style.display = "none";

        if (discountType === "percentage") {
            percentageFields.style.display = "block";
            minPurchaseField.style.display = "block";
        } else if (discountType === "fixed") {
            fixedFields.style.display = "block";
            minPurchaseField.style.display = "block";
        }
    }

    window.onload = function() {
        toggleCustomField();
    };
</script>
</html>
<?php $this->load->view("admin/footer"); ?>