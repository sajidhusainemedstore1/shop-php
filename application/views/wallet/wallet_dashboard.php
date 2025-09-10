<?php $this->load->view('admin/header'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wallet Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #06979A;
            padding: 20px;
        }
        h2 {
            color:#06979A;
            margin-bottom: 20px;
            text-align: center;
        }
        .search-box {
            margin-bottom: 10px;
        }
        input[type="search"] {
            padding: 8px;
            width: 250px;
            border: 1px solid #06979A;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        th, td {
            padding: 12px;
            border: 1px solid #06979A;
            text-align: left;
        }
        th {
            background-color: #06979A;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .action-icons {
            font-size: 16px;
            cursor: pointer;
        }
        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }
        
        .alert-danger {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
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

<h2>Dashboard Wallet</h2>

<div class="search-box">
    <label>Search: 
        <input type="search" id="searchInput" onkeyup="filterTable()">
    </label>
</div>

<form method="post" action="<?php echo base_url('wallet/update_wallet_percentage'); ?>" style="margin-bottom: 20px;">
    <label><strong>Wallet Percentage:</strong></label>
    <input type="number" name="wallet_percentage" value="<?php echo htmlspecialchars($wallet_percentage); ?>" min="0" max="40" step="0.1" required>
    <button type="submit">Update</button>
</form>

<table id="walletTable">
    <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Wallet Balance</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']) ?></td>
                    <td><?php echo htmlspecialchars($user['fullname']) ?></td>
                    <td>₹<?php echo number_format((float)$user['wallet_balance'], 2) ?></td>
                    <td><?php echo htmlspecialchars($user['email']) ?></td>
                    <td>
                        <a href="<?php echo base_url('wallet/edit/' . $user['id']) ?>" title="Edit">✏️</a>
                        <a href="<?php echo base_url('wallet/view/' . $user['id']) ?>" title="View">🔍</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No users found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
function filterTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.querySelectorAll("#walletTable tbody tr");

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}
</script>

</body>
</html>
<?php $this->load->view('admin/footer'); ?>