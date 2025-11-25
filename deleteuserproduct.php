<?php
    include 'includes/connection.php';
    $id=$_GET['id'];
    $query = "DELETE FROM purchase_list WHERE purchase_id='$id' ";
    $data= mysqli_query($con, $query);
    if($data){
        ?>

        <?php
        header('location:purchase_list.php');
    }

?>