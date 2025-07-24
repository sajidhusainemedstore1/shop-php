<?php $this->load->view("admin/header"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 40px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        form {
            background: #fff;
            max-width: 500px;
            margin: 0 auto;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #444;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="file"],
        input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        img {
            display: block;
            margin-bottom: 15px;
            border-radius: 5px;
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body>

<form method="post" action="<?php echo base_url('admin/update/' . $user['id']); ?>" enctype="multipart/form-data">
    <h2>Edit User</h2>

    <label>Full Name:</label>
    <input type="text" name="fullname" value="<?php echo $user['fullname']; ?>">

    <label>Email:</label>
    <input type="email" name="email" value="<?php echo $user['email']; ?>">

    <label>Mobile:</label>
    <input type="text" name="mobile" value="<?php echo $user['mobile']; ?>">

    <label>Pasword:</label>
    <input type="password" name="password" value="<?php $user['password']; ?>">

    <label>Upload New Image:</label>
    <input type="file" name="image">

    <label>Current Image:</label>
    <?php if (!empty($user['image'])) : ?>
        <img src="<?php echo base_url('uploads/' . $user['image']); ?>" alt="User Image">
    <?php else : ?>
        <p>No image uploaded</p>
    <?php endif; ?>

    <input type="submit" value="Update">
</form>

</body>
</html>
<?php $this->load->view("admin/footer"); ?>