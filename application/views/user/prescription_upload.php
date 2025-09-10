<?php $this->load->view("user/header"); ?>
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
        <div style="margin-bottom:15px;">
          <img src="<?php echo base_url('uploads/prescriptions/'.$presc['file']); ?>" 
               style="width:100px;border-radius:6px;">
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
    <img src="<?php echo base_url('assets/images/prescription-guide.png'); ?>" 
         alt="Prescription Guide" style="max-width:200px;">
  </div>
</div>
<?php $this->load->view("user/footer"); ?>
