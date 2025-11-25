<!DOCTYPE html>
<?php

session_start();
// if(isset($_SESSION['user_id'])){
//   header('location:updatepassword.php');
// }
include 'includes/connection.php';
$id = $_SESSION['id'];
$select = "SELECT * FROM users WHERE user_id='$id'";
$data = mysqli_query($con, $select);

while ($row = mysqli_fetch_array($data)) {
  $_SESSION['user_id'] = $row['user_id'];
  $password = $row['password'];
  $cpassword = $row['cpassword'];
}
if (isset($_POST['login'])) {
  $password = $_POST['password'];
  $cpassword = $_POST['cpassword'];

  $uppercase = preg_match('@[A-Z]@', $password);
  $lowercase = preg_match('@[a-z]@', $password);
  $number = preg_match('@[0-9]@', $password);

  //Password
  if (strlen($password) < 5) {
    $error = 'Password must be at least 5 characters';
  } elseif (!$uppercase || !$lowercase || !$number) {
    $error = " Password must contain at least one number & one uppercase & lowercase letter";
  }
  //Confirm password
  elseif ($cpassword != $password) {
    $error = "Password doesnot match";
  } else {
    // password encryption
    $password = md5($password);
    $cpassword = md5($cpassword);

    $update = "UPDATE users SET password ='{$password}', cpassword='$cpassword' WHERE user_id='{$id}' ";
    $query = mysqli_query($con, $update);

    session_destroy();
    header('location:login.php');
  }
}
?>
<html lang="en">

<head>
  <title>Update Password</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/form.css">
</head>

<body style="background-image:url(assets/images/update.jpg);background-repeat:no-repeat;background-position:center;background-size:cover;height:100vh;width:100%;">
  <div class="container">
    <div class="form-container">
      <div class="title"><span>Update Password</span></div>
      <form action="" method="POST">
        <p> <?php if (isset($error)) {
              echo $error;
            }  ?> </p>
        <p style="color:green;"><?php if (isset($sucess)) {
                                  echo $sucess;
                                } ?></p>
        <div class="row">
          <i class="fas fa-lock"></i>
          <i class="fa fa-eye" id="eye" onclick="pass()"></i>
          <input type="password" placeholder="Enter new password" name="password" id="password">
        </div>
        <div class="row">
          <i class="fas fa-lock"></i>
          <i class="fa fa-eye" id="eye" onclick="cpass()"></i>
          <input type="password" placeholder="Confirm Password" name="cpassword" id="cpassword">
        </div>
        <div class="row button">
          <input type="submit" value="Update" name="login">
        </div>
        <div class="signup-link">Already have an account? <a href="login.php">Login now</a></div>
      </form>
    </div>
  </div>
  <?php
  session_unset();
  session_destroy();
  ?>
  <script src="./assets/js/main.js"></script>
</body>

</html>