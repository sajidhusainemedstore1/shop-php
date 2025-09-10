<?php $this->load->view("user/header"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .container {
          max-width: 900px;
          margin: 30px auto;
          padding: 20px;
          font-family: Arial, sans-serif;
        }

        .container h2 {
          text-align: center;
          color: #06979A;
          font-size: 28px;
          margin-bottom: 30px;
        }

        .card {
          background: #fff;
          padding: 20px;
          border-radius: 10px;
          box-shadow: 0 2px 6px rgba(0,0,0,0.1);
          margin-bottom: 25px;
        }

        .card h3 {
          font-size: 20px;
          margin-bottom: 15px;
          color: #333;
          border-bottom: 2px solid #06979A;
          padding-bottom: 5px;
        }

        .card input[type="file"] {
          display: block;
          margin-top: 10px;
          margin-bottom: 10px;
          padding: 6px;
          border: 1px solid #ccc;
          border-radius: 6px;
        }

        .btn {
          background-color: #06979A;
          color: #fff;
          padding: 8px 15px;
          border: none;
          border-radius: 6px;
          text-decoration: none;
          font-size: 14px;
          cursor: pointer;
          display: inline-block;
          transition: background-color 0.3s ease;
        }

        .btn:hover {
          background-color: #048084;
        }

        .prescription-item {
          display: flex;
          align-items: center;
          gap: 15px;
          margin-bottom: 15px;
        }

        .prescription-item img {
          width: 100px;
          border-radius: 6px;
          border: 1px solid #ddd;
        }

        .prescription-item .btn {
          flex-shrink: 0;
        }

        .card ul {
          padding-left: 20px;
          margin-bottom: 10px;
        }

        .card ul li {
          margin-bottom: 5px;
        }

        @media (max-width: 600px) {
          .prescription-item {
            flex-direction: column;
            align-items: flex-start;
          }
          .prescription-item img {
            width: 80px;
          }
        }
    </style>
</head>
<body>
    <div class="container">
      <h2>PRESCRIPTION</h2>

      <div class="card">
        <h3>Upload Prescription</h3>
        <form action="<?php echo base_url('user/save_prescription'); ?>" 
              method="post" enctype="multipart/form-data">
          <input type="file" name="prescription_file" 
                 accept=".jpeg,.jpg,.png,.pdf" required>
          <small style="color:red;">Allow Only (.jpeg,.jpg,.png,.pdf) & Max size 8MB</small>
          <br><br>
          <button type="submit" class="btn">Upload Prescription</button>
        </form>
      </div>

      <div class="card">
        <h3>My Prescriptions</h3>
        <?php if(!empty($my_prescriptions)): ?>
          <?php foreach($my_prescriptions as $presc): ?>
            <div class="prescription-item">
              <img src="<?php echo base_url('uploads/'.$presc['file']); ?>" alt="Prescription">
              <a href="<?php echo base_url('user/select_prescription/'.$presc['id']); ?>" 
                 class="btn">Select</a>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>No prescriptions found.</p>
        <?php endif; ?>
      </div>

      <div class="card">
        <h3>Prescription Guide</h3>
        <ul>
          <li>Upload clear prescription images</li>
          <li>Must include Doctor details, Patient details, Medicine details, Doctor Sign & Stamp</li>
        </ul>
        <img src="<?php echo base_url('uploads/in.png'); ?>" 
             alt="Prescription Guide" style="max-width:200px;">
      </div>
    </div>

<?php $this->load->view("user/footer"); ?>
</body>
</html>