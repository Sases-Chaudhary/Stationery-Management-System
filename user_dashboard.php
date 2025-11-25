<!DOCTYPE html>
<?php
session_start();
if(!isset($_SESSION['username'])){
	header('location:login.php');
}
include './includes/connection.php';
$id = $_SESSION['user_id'];

?>
<html lang="en">
<head>
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/add.css">
	<title>Stationery Management System</title>
</head>
<body>
	
    <!-- SIDEBAR -->
	<section id="sidebar"> 
		<?php 
			if($_SESSION['user_type'] == 1){
				include 'layouts/user_menu.php';
			}
			else{
				header( 'location:admin_dashboard.php');
			}
		?>

  <!-- Main Content -->
  <main >
    <h1 class="title">Dashboard</h1>
    <ul class="breadcrumbs">
        <li><a href="user_dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="user_dashboard.php" class="active">dashboard</a></li>
    </ul>
	<div class="info-data">
		<div class="card">
			<div class="head">
				<div>
					<h2>Welcome</h2>
					<span style="color:blue;font-size:18px;font-weight:bold;"><?php echo $_SESSION['username'];?></span>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="head">
				<div>
					<h2>Products</h2>
					<?php
						$check_product = "SELECT * FROM product ";
						$query_product = mysqli_query($con,$check_product);
						$row_product = mysqli_num_rows($query_product);
					?>
					<p><?php echo $row_product;?></p>
				</div>
				<i class='bx bxs-doughnut-chart icon' style="color:green;"></i>
			</div>
		</div>
	</div>
	<div class="data">
		<!-- <div class="content-data">
			<div class="head">
				<h3>Sales Report</h3>
			</div>
			<div class="chart">
				<div id="chart"></div>
			</div>
		</div> -->
		<div class="content-data">
			<div class="head">
				<h3>Recent Orders</h3>
			</div>
			<div class="table">
            <section class="table_body">
                <table >
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Product Added</th>
                        </tr>
                    </thead>
                    <tbody>
					<?php
                        $select_product = mysqli_query($con, "SELECT * FROM purchase_list WHERE user_id='$id' ORDER BY purchase_date DESC LIMIT 7");
                        if(mysqli_num_rows($select_product) > 0){
                           while( $fetch_purchase = mysqli_fetch_assoc($select_product)){
                            $p_id= $fetch_purchase['purchase_id'];
                           
                        ?>
                        <tr>
                            <td><?php  echo $fetch_purchase['product_name']?></td>
                            <td><?php  echo $fetch_purchase['product_quantity'] ?></td>
                            <td><?php echo $fetch_purchase['purchase_date'] ?></td>
                            
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
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="assets\js\dashboard.js"></script>
</body>
</html>