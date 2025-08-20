<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .login-container {
            width: 360px;
            margin: 100px auto;
            padding: 30px;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #06979A;
            border: none;
            color: white;
            border-radius: 3px;
            cursor: pointer;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Users Login</h2>

    <?php if (!empty($error)) : ?>
        <div class="error"><?php echo $error ?></div>
    <?php endif; ?>

    <form method="post" action="<?php echo base_url('user/index') ?>">
        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" placeholder="Username" required />
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" placeholder="Password" required />
        </div>
        <input type="submit" value="Login" />
        <p>Don’t have an Account? <a href="<?php echo base_url('user/signup'); ?>">Signup here</a></p>

    </form>
</div>

</body>
</html>
