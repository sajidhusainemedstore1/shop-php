<?php $this->load->view("admin/header"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <link rel="stylesheet" href="<?php //echo base_url('assets/css/style.css') ?>"> -->
     <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            padding: 20px;
        }

        .form-title {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .product-form {
            max-width: 300px;
            margin: 0 auto;
            padding: 25px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #555;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea,
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group img {
            margin-top: 8px;
            border: 1px solid #ccc;
            padding: 5px;
            border-radius: 5px;
            background-color: #fafafa;
        }

        .submit-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .submit-btn:hover {
            background-color: #218838;
        }
    </style>
</head>

</head>
<body>
    <h2 class="form-title">Edit Product</h2>

<form class="product-form" method="post" action="<?php echo base_url('admin/update_product/' . $product['id']) ?>" enctype="multipart/form-data">
    <div class="form-group">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo $product['name']; ?>" required>
    </div>

    <div class="form-group">
        <label>Description:</label>
        <textarea name="description" rows="4"><?php echo $product['description']; ?></textarea>
    </div>

    <div class="form-group">
        <label>Price:</label>
        <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>
    </div>

    <div class="form-group">
        <label>Old Image:</label><br>
        <img src="<?php echo base_url('uploads/' . $product['image']) ?>" width="120" style="border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
    </div>

    <div class="form-group">
        <label>Change Image:</label>
        <input type="file" name="image">
    </div>

    <div class="form-group">
        <input type="submit" value="Update Product" class="submit-btn">
    </div>
</form>

</body>
</html>
<?php $this->load->view("admin/footer"); ?>