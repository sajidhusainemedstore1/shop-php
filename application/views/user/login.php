<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
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
            width: 35%;
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

        a {
            text-decoration: none;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            width: 100%;
            padding-right: 40px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .password-wrapper i {
            position: absolute;
            right: 1px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
        }

    </style>
</head>
<body>

<div class="login-container">
    <h2>Sign up to your Account</h2>

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
            <div class="password-wrapper">
                <input type="password" name="password" id="id_password" placeholder="Password" required />
                <i class="far fa-eye" id="togglePassword"></i>
            </div>
        </div>
        <input type="submit" value="Login" />
        <p>Don’t have an Account? <a href="<?php echo base_url('user/signup'); ?>">Register Account</a></p>
    </form>
</div>
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#id_password');

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
</script>
</body>
</html>
