<!DOCTYPE html>
<?php 
    session_start();
    if(!isset($_SESSION['username'])){
        header('location:login.php');
    }
	include 'includes/connection.php';
    $user=$_SESSION['username'];
    $check = "SELECT * FROM users WHERE username='$user'  ";
    $query = mysqli_query($con,$check);
    $row = mysqli_num_rows($query);
  
    if($row == 1){

      while($row= mysqli_fetch_array($query)){
        $id = $row['user_id'];
        $username = $row['username'];
        $email = $row['email'];
        $pass = $row['password'];
      }
    } 
    ?>
<?php
    if (isset($_POST['changepass'])) {
        $opass = $_POST['opass'];
        $password = $_POST['npass'];
        $cpass = $_POST['cpass'];

        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);

        //Password
        if($pass != md5($opass)){
            $error = 'Old password is incorrect';

        }
        else{
            if (strlen($password) < 5) {
                $error = 'New Password must be at least 5 characters';
            } elseif (!$uppercase || !$lowercase || !$number) {
                $error = " New Password must contain at least one number & one uppercase & lowercase letter";
            }
            //Confirm password
            elseif ($cpass != $password) {
                $error = "New password and Confrim Password doesn't match";
            } else {
                //password encryption
                $password = md5($password);
                $cpassword = md5($cpassword);

                $update = "UPDATE users SET password ='{$password}', cpassword='$cpassword' WHERE user_id='{$id}' ";
                $query = mysqli_query($con, $update);
                if($query){
                    header('location:profile.php');
                }
            }
        }
    }
?>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Stationery Management System</title>
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/profile.css">
	<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
	<span class="overlay"></span>
    <!-- SIDEBAR -->
<section id="sidebar"> 
    <?php 
        if($_SESSION['user_type'] == 1){
            include 'layouts/user_menu.php';
        }
        else{
            include 'layouts/admin_menu.php';
        }
    ?>
    <main>
        <section>
            <h3 class="m-4">Account Settings</h3>
            <div class="container">
                <div class="bg-white shadow rounded-lg d-block d-sm-flex">
                    <div class="profile-tab-nav border-right">
                        <div class="p-2">
                            <h4 class="text-center"><?php  echo $username;?></h4>
                        </div>
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link " id="account-tab" data-toggle="pill" href="profile.php" role="tab" aria-controls="account" aria-selected="true">
                                <i class="fa fa-home text-center mr-1"></i> 
                                Account
                            </a>
                            <a class="nav-link active" id="password-tab" data-toggle="pill" href="password.php" role="tab" aria-controls="password" aria-selected="false">
                                <i class="fa fa-key text-center mr-1"></i> 
                                Password
                            </a>
                        </div>
                    </div>
                    
                        <div class="tab-pane m-4" id="password" role="tabpanel" aria-labelledby="password-tab">
                            <h3 class="mb-4">Password Settings</h3>
                            <p style="color: red;text-align:center;"> <?php if(isset($error)){ echo $error; }?></p>
                            <form action="" method="POST">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Old password</label>
                                            <input type="password" class="form-control" name="opass">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>New password</label>
                                            <input type="password" class="form-control" name="npass">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Confirm new password</label>
                                            <input type="password" class="form-control" name="cpass">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <button class="btn btn-primary" name="changepass">Update</button>
                                </div>
                            </form>
                        
                    </div>
                </div>
            </div>
        </section>
    </main>
</section>
<script src="assets\js\dashboard.js"></script>
</body>
</html>    