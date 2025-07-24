<?php $this->load->view("admin/header"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Product List</title>
    <style>
        table {
            width: 100%; border-collapse: collapse; margin-top: 20px;
        }
        th, td {
            padding: 10px; border: 1px solid #ccc; text-align: left;
        }
        .btn {
            padding: 6px 12px; background-color: #28a745;
            color: #fff; border: none; border-radius: 4px; cursor: pointer;
        }
        .btn:hover {
            background-color: #218838;
        }
        img {
            max-width: 80px;
            height: auto;
        }
    </style>
</head>
<body>

<h2>Admin - Product List</h2>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Image</th>
            <th>Add to Cart</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?php echo $product['name']; ?></td>
            <td>₹<?php echo number_format($product['price'], 2); ?></td>
            <td>
                <img src="<?php echo base_url('uploads/' . $product['image']); ?>" alt="<?php echo $product['name']; ?>">
            </td>
            <td>
                <form method="post" action="<?php echo base_url('admin/add_to_cart'); ?>">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" class="btn">Add to Cart</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
<?php $this->load->view("admin/footer"); ?>