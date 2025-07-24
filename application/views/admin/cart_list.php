<?php $this->load->view('admin/header'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Cart List</title>
    <style>
        body { font-family: Arial; background-color: #f8f9fa; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background-color:rgb(36, 206, 197); color: white; }
        h2 { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h2>All Cart Items</h2>
    <a href="<?php echo base_url('admin/dashboard') ?>">Dashboard</a>
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

    <table>
        <thead>
            <tr>
                <th>Cart ID</th>
                <th>User</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
                <th>Added At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="cartBody">
        </tbody>
    </table>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function loadCartItems() {
            $.ajax({
                url: "<?php echo base_url('admin/get_cart_items_ajax'); ?>",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    let html = '';
                    if (data.length > 0) {
                        data.forEach(function(item) {
                            html += `
                                <tr>
                                    <td>${item.id}</td>
                                    <td>${item.fullname}</td>
                                    <td>${item.product_name}</td>
                                    <td>${item.qty}</td>
                                    <td>${parseFloat(item.price).toFixed(2)}</td>
                                    <td>${parseFloat(item.subtotal).toFixed(2)}</td>
                                    <td>${item.added_at}</td>
                                    <td>
                                        <a href="<?php echo base_url('admin/edit_cart/'); ?>${item.id}" style="margin-right:10px; color:green;">Edit</a>
                                        <a href="#" onclick="deleteCartItem(${item.id})" style="color:red;">Delete</a>
                                    </td>
                                </tr>`;
                        });
                    } else {
                        html = '<tr><td colspan="8" style="text-align:center;">No cart items found.</td></tr>';
                    }
                    $('#cartBody').html(html);
                }
            });
        }

        function deleteCartItem(id) {
            if (confirm('Are you sure you want to delete this cart item?')) {
                $.ajax({
                    url: "<?php echo base_url('admin/delete_cart_ajax/'); ?>" + id,
                    type: "POST",
                    success: function(response) {
                        alert("Item deleted.");
                        loadCartItems();
                    }
                });
            }
        }

        $(document).ready(function() {
            loadCartItems();
        });
    </script>
</body>
</html>
<?php $this->load->view('admin/footer'); ?>