<!DOCTYPE html>
<?php
session_start();
if(isset($_SESSION['username'])){
  header('location:user_dashboard.php');
}
require_once "./includes/connection.php";
if (isset($_POST['submit'])) {
  $username = mysqli_real_escape_string($con, $_POST['username']);
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $password = mysqli_real_escape_string($con, $_POST['password']);
  $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
  $type = 1;
  $verify_token = md5(rand());

  //Validation
  $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";  
  $uppercase = preg_match('@[A-Z]@', $password);
  $lowercase = preg_match('@[a-z]@', $password);
  $number = preg_match('@[0-9]@', $password);
  //username
  if(strlen($username)<4){
    $error= 'Username must be at least 4 characters';
  }
  elseif(!preg_match("/^[a-zA-Z]*$/",$username)){
    $error = "Only letters with no white space are allowed in username";
  }
  // Email
  elseif(empty($email)){
    $error= 'Email is required';
  }
  elseif(!preg_match ($pattern, $email) ){  
    $error = "Email is not valid.";  
  } 
  //Password
  elseif(strlen($password)<5){
    $error= 'Password must be at least 5 characters';
  }
  elseif(!$uppercase || !$lowercase || !$number ) {
    $error = " Password must contain at least one number & one uppercase & lowercase letter";
  }
  //Confirm password
  elseif($cpassword != $password){
    $error ="Password doesnot match";
  }
  else{
    // Check if email already exists
    $emailquery = "SELECT * FROM users WHERE email = '{$email}'";
    $query = mysqli_query($con, $emailquery);
    $emailcount = mysqli_num_rows($query);

    if($emailcount > 0){
      $error = "Email already exists";
      header('location:register.php');
    }
    else{
      // Hash the password
      // $password= md5($password);

      // Insert user data into the database
      $insertquery = "INSERT INTO users (username, email, password, cpassword, user_type) VALUES('{$username}','{$email}','{$password}','{$password}','{$type}')";
      $iquery = mysqli_query($con, $insertquery);

      // Redirect to login page if registration is successful
      if($iquery){
        header('location:login.php');
      }
      else{
        header('location:register.php');
      }
    }
  }
}

?>
<html lang="en">
<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/form.css">
</head>
<body style="background-image:url(assets/images/register.jpg);background-repeat:no-repeat;background-size:cover;;height:100vh;width:100%;">
<div class="container">
      <div class="form-container">
        <div class="title"><span>Register Now</span></div>
        <form action="register.php" method="POST">
        <p> <?php if(isset($error)){ echo $error; }?></p>
        <div class="row">
          <i class="fas fa-user"></i>
          <input type="text" placeholder="Username" name="username" value="<?php if(isset($error)){ echo $username; }?>">
        </div>
        <div class="row">
          <i class="fa fa-envelope" aria-hidden="true"></i>
          <input type="text" placeholder="Email" name="email" value="<?php if(isset($error)){ echo $email; }?>">
        </div>
        <div class="row">
          <i class="fas fa-lock"></i>
          <i class="fa fa-eye"  id="eye"   onclick="pass()"></i>
          <input type="password" placeholder="Password" name="password" value="<?php if(isset($error)){ echo $password; }?>" id="password">
        </div>
        <div class="row">
          <i class="fas fa-lock"></i>
          <i class="fa fa-eye"  id="eye"   onclick="cpass()"></i>
          <input type="password" placeholder="Confirm Password" name="cpassword" value="<?php if(isset($error)){ echo $cpassword; } ?>" id="cpassword">
        </div>
          <div class="row button">
            <input type="submit" value="Register" name="submit">
          </div>
          <div class="signup-link">Already have an account? <a href="login.php">Login now</a></div>
        </form>
      </div>
    </div>
    <script src="./assets/js/main.js"></script>
</body>
</html>