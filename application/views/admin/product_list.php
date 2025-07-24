<?php $this->load->view('admin/header'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
        <!-- <link rel="stylesheet" href="<?php //echo base_url('assets/css/style.css') ?>"> -->
         <style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        background-color: #f9f9f9;
    }

    .header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border-radius: 5px;
    }

    .add-btn {
        text-decoration: none;
        background-color: #28a745;
        color: white;
        padding: 8px 15px;
        border-radius: 4px;
        font-weight: bold;
    }

    .add-btn:hover {
        background-color: #218838;
    }

    .page-title {
        margin: 0;
    }

    .product-table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 6px;
        overflow: hidden;
    }

    .product-table th, .product-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .product-table th {
        background-color: #f1f1f1;
        font-weight: bold;
    }

    .product-img {
        border-radius: 5px;
        height: auto;
    }

    .action-link {
        text-decoration: none;
        font-weight: bold;
        padding: 4px 8px;
        border-radius: 4px;
    }

    .action-link.edit {
        color: #007bff;
    }

    .action-link.delete {
        color: #dc3545;
    }

    .action-link:hover {
        text-decoration: underline;
    }

    @media (max-width: 600px) {
        .header-bar {
            flex-direction: column;
            align-items: flex-start;
        }

        .product-table th, .product-table td {
            padding: 10px;
            font-size: 14px;
        }

        .add-btn {
            margin-bottom: 10px;
        }
    }
</style>
</head>
<body>

<div class="header-bar">
    <h2 class="page-title">Product List</h2>
</div>


<table class="product-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?php echo $product['name']; ?></td>
            <td>₹<?php echo number_format($product['price'], 2); ?></td>
            <td><img src="<?php echo base_url('uploads/' . $product['image']); ?>" width="80" class="product-img"></td>
            <td>
                <a href="<?php echo base_url('admin/edit_product/' . $product['id']); ?>" class="action-link edit">Edit</a> |
                <a href="<?php echo base_url('admin/delete_product/' . $product['id']); ?>" class="action-link delete" onclick="return confirm('Delete this product?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
<?php $this->load->view('admin/footer'); ?>