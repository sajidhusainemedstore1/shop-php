<?php $this->load->view("user/header"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Shop - Product List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f4f4f4;
        }
        h2 {
            margin-bottom: 20px;
        }
        .products {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .product-card {
            background: white;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            padding: 15px;
            width: 200px;
            text-align: center;
        }
        .product-card img {
            max-width: 100%;
            height: 140px;
            object-fit: contain;
            margin-bottom: 10px;
        }
        .product-name {
            font-weight: bold;
            margin-bottom: 5px;
            height: 40px;
            overflow: hidden;
        }
        .product-price {
            color: #28a745;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .btn-add {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-add:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h2>Available Products</h2>

<div class="products">
    <?php foreach ($products as $product): ?>
    <div class="product-card">
        <img src="<?php echo base_url('uploads/' . $product['image']); ?>" alt="<?php echo $product['name']; ?>">
        <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
        <div class="product-price">₹<?php echo number_format($product['price'], 2); ?></div>
        <form method="post" action="<?php echo base_url('admin/add_to_cart'); ?>">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <button type="submit" class="btn-add">Add to Cart</button>
        </form>
    </div>
    <?php endforeach; ?>
</div>

</body>
</html>
<?php $this->load->view("user/footer"); ?>