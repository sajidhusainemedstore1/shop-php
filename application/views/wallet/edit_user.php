<?php $this->load->view("admin/header"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Wallet</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f8f9fa; }
        form { max-width: 400px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 8px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 20px; }
        label { display: block; margin-top: 10px; }
        input, select, button {
            width: 100%; padding: 10px; margin-top: 5px;
            border: 1px solid #ccc; border-radius: 4px;
        }
        button {
            background-color: #06979A; color: white;
            margin-top: 15px;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
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

<?php
    $user_id = $this->session->userdata('user_id');
    $user_logged_in = $this->session->userdata('user_logged_in');
    $cart_count = $this->cart_model->count_items($user_id);
?>

<h2>Edit Wallet for <?php echo htmlspecialchars($user['fullname']) ?></h2>

<p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']) ?></p>
<p><strong>Current Wallet Balance:</strong> ₹<?php echo number_format((float)$user['wallet_balance'], 2) ?></p>

<form method="post" action="<?php echo base_url('wallet/update_balance/' . $user['id']) ?>">
    <label>Amount:</label>
    <input type="number" name="amount" min="0.1" step="0.01" required>

    <label>Transaction Type:</label>
    <select name="type" required>
        <option>----Select----</option>
        <option value="credit">Credit</option>
        <option value="debit">Debit</option>
    </select>

    <label>Description (optional):</label>
    <input type="text" name="description">

    <button type="submit">Submit</button>
</form>

</body>
</html>
<?php $this->load->view("admin/footer"); ?>