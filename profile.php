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
        $_SESSION['email'] = $row['email'];
        $pass = $row['password'];
      }
    } 
?>
<!-- General account Update -->
<?php
if(isset($_POST['general'])){
    $name = $_POST['username'];
    $email_update=$_POST['email'];
    $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";  

    //username
    if(strlen($name)<4){
        $error= 'Username must be at least 4 characters';
    }
    elseif(!preg_match("/^[a-zA-Z]*$/",$name)){
        $error = "Only letters with no white space are allowed in username";
    }
    // Email
    elseif(empty($email_update)){
        $error= 'Email is required';
    }
    elseif(!preg_match ($pattern, $email_update) ){  
        $error = "Email is not valid.";  
    } 
    else{
        //insert into database
        $update = "UPDATE users SET username ='{$name}', email='$email_update' WHERE user_id='{$id}' ";
        $iquery = mysqli_query($con,$update);
        if($iquery){
            $_SESSION['username']=$name;
            header('location:profile.php');
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
                            <h4 class="text-center"><?php  echo $_SESSION['username'];?></h4>
                        </div>
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active" id="account-tab" data-toggle="pill" href="profile.php" role="tab" aria-controls="account" aria-selected="true">
                                <i class="fa fa-home text-center mr-1"></i> 
                                Account
                            </a>
                            <a class="nav-link" id="password-tab" data-toggle="pill" href="password.php" role="tab" aria-controls="password" aria-selected="false">
                                <i class="fa fa-key text-center mr-1"></i> 
                                Password
                            </a>
                        </div>
                    </div>
                    <div class="tab-content p-4 p-md-4" id="v-pills-tabContent">
                        <div class="tab-pane active" id="account" role="tabpanel" aria-labelledby="account-tab">
                            <h3 class="mb-2">Account Settings</h3>
                            <p style="color: red;text-align:center;"> <?php if(isset($error)){ echo $error; }?></p>

                            <form action="" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>User Name</label>
                                        <input type="text" class="form-control" value="<?php  echo $_SESSION['username'];?>" name="username">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" class="form-control" value="<?php echo $_SESSION['email']; ?>" name="email">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-primary" name="general">Update</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</section>
<script src="assets\js\dashboard.js"></script>
</body>
</html>   