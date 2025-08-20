<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        footer {
            position: relative;
            background-color: #06979A;
            color: white;
            padding: 10px 20px;
            height: 60px; /* adjust if needed */
        }
        
        .footer-center {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            font-size: 20px;
            font-weight: bold;
        }
        
        footer h4{
            margin: 0;
        }

    </style>
</head>
<body>
    <div>
        <footer>
            <div class="footer-center">
                <h4><p> Copyright &copy; <?php echo date('Y') ?> Shop. All rights reserved.</p></h4>
            </div>
        </footer>
    </div>
</body>
</html>