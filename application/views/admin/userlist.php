<?php $this->load->view('admin/header'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>User List</title>
<style>
  body {
    font-family: Arial, sans-serif;
    margin: 40px;
    background: #f9f9f9;
  }
  h2 {
    text-align: center;
    margin-bottom: 25px;
  }
  table {
    width: 80%;
    margin: 0 auto;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 0 8px rgba(0,0,0,0.1);
  }
  th, td {
    padding: 12px 15px;
    border: 1px solid #06979A;
    text-align: left;
  }
  th {
    background-color: #06979A;
    color: white;
  }
  tr:nth-child(even) {
    background-color: #f2f2f2;
  }
  .logout {
    width: 80%;
    margin: 20px auto;
    text-align: right;
  }
  .logout a {
    color: #06979A;
    text-decoration: none;
    font-weight: bold;
  }
</style>
</head>
<body>

<h2>User List</h2>
<div class="logout">
</div>

<table>
  <thead>
    <tr>
      <th>Images</th>
      <th>Fullname</th>
      <th>Email</th>
      <th>Mobile</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($user)) : ?>
      <?php foreach ($user as $user) : ?>
<tr>
  <td>
    <?php if (!empty($user['image'])) : ?>
      <img src="<?php echo base_url('uploads/' . $user['image']); ?>" alt="User Image" width="60" height="60" style="object-fit: contain; border-radius: 5px; background: #eee; border-radius: 5px;" />
    <?php else : ?>
      No image
    <?php endif; ?>
  </td>
  <td><?php echo ($user['fullname']); ?></td>
  <td><?php echo ($user['email']); ?></td>
  <td><?php echo ($user['mobile']); ?></td>
  <td>
    <a href="<?php echo base_url('admin/edit/' . $user['id']); ?>" style="margin-right: 10px; color: #06979A; text-decoration: none;">Edit</a>
    <a href="<?php echo base_url('admin/delete/' . $user['id']); ?>" onclick="return confirm('Are you sure you want to delete this user?');" style="color: #06979A; text-decoration: none;">Delete</a>
  </td>
</tr>


      <?php endforeach; ?>
    <?php else : ?>
      <tr><td colspan="4" style="text-align:center;">No users found.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

</body>
</html>
<?php $this->load->view('admin/footer'); ?>
