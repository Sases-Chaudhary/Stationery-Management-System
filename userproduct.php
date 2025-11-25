<!DOCTYPE html>
<?php 
    session_start();
    if(!isset($_SESSION['user_id'])){
        header('location:login.php');
    }
	include 'includes/connection.php';
    $id = $_SESSION['user_id'];
    $sucess = $_SESSION['sucess'];
?>
<html lang="en">
<head>
    <title>Stationery Management System</title>
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/add.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
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
    <?php
        $select_user = mysqli_query($con, "SELECT * FROM users WHERE user_id='$id'");
        if(mysqli_num_rows($select_user) > 0){
            $fetch_user = mysqli_fetch_assoc($select_user);
        }
        // echo $id;
    ?>
<!-- Main Content -->
<main >
    <h1 class="title">Product</h1>
    <ul class="breadcrumbs">
        <li><a href="user_dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="userproduct.php" class="active">Product</a></li>
    </ul>
    <!-- <p><?php if(isset($sucess)){echo $sucess;}?></p> -->
    <div class="content">
        <div class="table">
            <section class="table_body">
                <table id="table" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Product Image</th>
                            <th>Product Rate(R.s)</th>
                            <th>Product Quantity</th>
                            <th>Add Order</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $select_product = mysqli_query($con, "SELECT * FROM product");
                        if(mysqli_num_rows($select_product) > 0){
                           while( $fetch_product = mysqli_fetch_assoc($select_product)){
                            $p_id= $fetch_product['product_id'];
                           
                        ?>
                        <tr>
                            <td><?php  echo $fetch_product['product_name']?></td>
                            <td><img src="<?php echo "images/".$fetch_product['product_image']; ?>" alt="image" width="100px" height="100px"></td>
                            <td><?php  echo $fetch_product['product_bought'] ?></td>
                            <td><?php  echo $fetch_product['product_stock'] ?></td>
                            <td><a href="purchase.php?id=<?php echo $p_id; ?>"><i class='bx bx-cart' style="font-size:25px;color:black;"></i></a></td>
                        </tr>
                        <?php
                           }
                        }
                        
                    ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
</main>
<!-- Main Content -->
</section>
    <script src="assets\js\dashboard.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#table').DataTable();
        });
    </script>
</body>
</html>    