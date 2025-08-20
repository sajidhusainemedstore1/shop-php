<?php $this->load->view("user/header"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Address</title>
    <style>
        .container {
            background: #fff;
            padding: 20px;
            max-width: 600px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
        }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea { width: 95%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        .btn { padding: 10px 20px; background: #06979A; color: white; border: none; border-radius: 5px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Add New Address</h2>
    <form method="post" action="<?php echo base_url('user/save_address') ?>">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label>Mobile</label>
            <input type="text" name="mobile" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email">
        </div>
        <div class="form-group">
            <label>Address</label>
            <textarea name="address" required></textarea>
        </div>
        <div class="form-group">
            <label>City</label>
            <input type="text" name="city" required>
        </div>
        <div class="form-group">
            <label>State</label>
            <input type="text" name="state" required>
        </div>
        <div class="form-group">
            <label>Pincode</label>
            <input type="text" name="pincode" required>
        </div>
        <div class="form-group">
            <label><input type="checkbox" name="is_default" value="1"> Set as default</label>
        </div>
        <button type="submit" class="btn">Save Address</button>
    </form>
</div>

</body>
</html>
<?php $this->load->view("user/footer"); ?>