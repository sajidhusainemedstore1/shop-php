<?php $this->load->view("user/header"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .contact-form-container {
            flex: 1;
            min-width: 300px;
        }
        .contact-form-container h2 {
            margin-top: 0;
            color: #333;
        }
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }
        .form-group {
            flex: 1;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group textarea {
            resize: vertical;
            height: 100px;
        }
        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
        }
        .recaptcha {
            margin-top: 15px;
        }
        .submit-btn {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
        .map-container {
            flex: 1;
            min-width: 300px;
        }
        .map-wrapper {
            width: 100%;
            height: 400px;
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
        }
        .map-wrapper iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }
        .map-info {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
        }
    </style>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>

<div class="container">
    <div class="contact-form-container">
        <h2>Contact Us</h2>
        <form action="#" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Name" >
                </div>
                <div class="form-group">
                    <label for="mobile">Mobile</label>
                    <input type="tel" id="mobile" name="mobile" placeholder="Mobile">
                </div>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" placeholder="Enquiry *" required></textarea>
            </div>
            <div class="recaptcha">
                <?php if ($this->session->flashdata('recaptcha_error')): ?>
    <div style="color: red; margin-top: 10px;">
        <?php echo $this->session->flashdata('recaptcha_error'); ?>
    </div>
<?php endif; ?>

<label style="margin-top:20px;">Captcha Verification:</label>
<div class="g-recaptcha" data-sitekey="<?php echo $this->config->item('google_recaptcha_site_key'); ?>"></div>
            </div>
            <button type="submit" class="submit-btn">SEND MESSAGE</button>
        </form>
    </div>

    <div class="map-container">
        <div class="map-wrapper">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3672.878403961877!2d72.50774338022053!3d22.99149846386515!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e9ac93b950b23%3A0x53182fc10f757949!2sAl%20Asbab%20Park%2C%20D4%2C%20Al%20Asbab%20Society%2C%20Makarba%2C%20Ahmedabad%2C%20Gujarat%20380055!5e0!3m2!1sen!2sin!4v1755779028086!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</div>

</body>
</html>
<?php $this->load->view("user/footer"); ?>