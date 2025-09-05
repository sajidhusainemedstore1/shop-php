<?php $this->load->view("admin/header"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
        <style>
        .container {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 40px;
            display: flex;
            justify-content: center;
        }

        .form-title {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .error-msg {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .product-form {
            background: #fff;
            padding: 25px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px #333;
            max-width: 350px;
            width: 100%;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
            color: #333;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="file"],
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group textarea {
            resize: vertical;
        }

        .submit-btn {
            background-color: #06979A;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #1b5e5fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($error)) echo '<p class="error-msg">' . $error . '</p>'; ?>

        <form class="product-form" method="post" action="<?php echo base_url('admin/save_product') ?>" enctype="multipart/form-data">
            <h2 class="form-title">Add Product</h2>
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label>Price:</label>
                <input type="number" step="0.01" name="price" required>
            </div>

            <div class="form-group">
                <label>Image:</label>
                <input type="file" name="image" required>
            </div>

            <div class="form-group">
                <input type="submit" value="Add Product" class="submit-btn">
            </div>

        </form>
    </div>
</body>
</html>
<?php $this->load->view("admin/footer"); ?>