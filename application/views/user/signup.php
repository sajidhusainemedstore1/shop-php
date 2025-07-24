<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>User_Signup</title>
<style>
  * {
    box-sizing: border-box;
  }

  body {
    background: #f0f2f5;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    min-height: 100vh;
    align-items: center;
    justify-content: center;
  }

  .container {
    background: white;
    padding: 30px 40px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    max-width: 400px;
    width: 100%;
  }

  h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #333;
  }

  .error {
    background-color: #f8d7da;
    color: #842029;
    padding: 12px 15px;
    border-radius: 5px;
    margin-bottom: 20px;
    border: 1px solid #f5c2c7;
    font-size: 0.9rem;
    text-align: center;
  }

  form label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #555;
    margin-top: 15px;
  }

  input[type="text"],
  input[type="email"],
  input[type="password"] {
    width: 100%;
    padding: 10px 12px;
    border: 1.5px solid #ccc;
    border-radius: 5px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
  }

  input[type="text"]:focus,
  input[type="email"]:focus,
  input[type="password"]:focus {
    border-color: #007bff;
    outline: none;
  }

  button[type="submit"] {
    margin-top: 25px;
    width: 100%;
    padding: 12px;
    background-color: #007bff;
    border: none;
    border-radius: 6px;
    font-size: 1.1rem;
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.25s ease;
  }

  button[type="submit"]:hover {
    background-color: #0056b3;
  }

  /* Responsive adjustments */
  @media (max-width: 480px) {
    .container {
      padding: 20px 25px;
    }
  }
  .toggle-password1 {
    float: right;
    top: -25px;
    right: 10px;
    position: relative;
    cursor: pointer;
}
  .toggle-password {
    float: right;
    top: -25px;
    right: 10px;
    position: relative;
    cursor: pointer;
}
</style>
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
<body>
<div class="container">
    <h2>Signup</h2>

    <?php if (!empty($error)) : ?>
        <div class="error"><?php echo ($error) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" action="<?php echo base_url('user/signup'); ?>">

        <label for="fullname">Fullname:</label>
        <input type="text" id="fullname" name="fullname" placeholder="Full Name" required />

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Email" required />

        <label for="mobile">Mobile:</label>
        <input type="text" id="mobile" name="mobile" placeholder="Mobile Number" required />

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Password" required />
        <span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-password"></span>

        <label for="Con_Password">Confirm Password:</label>
        <input type="password" id="Con_Password" name="Con_Password" placeholder="Confirm Password" required />
        <span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-password1" ></span>

        <label for="image">Image Uploads</label>
        <input type="file" id="image" name="image" required />
        <button type="submit">Signup</button>
    </form>
</div>
<script>
$(document).on('click', '.toggle-password', function() {

    $(this).toggleClass("fa-eye fa-eye-slash");
    
    var input = $("#password");
    input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
});

$(document).on('click', '.toggle-password1', function() {

    $(this).toggleClass("fa-eye fa-eye-slash");
    
    var input = $("#Con_Password");
    input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
});
</script>
</body>
</html>
