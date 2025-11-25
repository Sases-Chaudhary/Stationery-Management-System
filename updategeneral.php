<?php
	include 'includes/connection.php';
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
        header('location:user_dashboard.php');
        }
    }
    
} 
?>