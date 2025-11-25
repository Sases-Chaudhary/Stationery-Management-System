<!DOCTYPE html>
<?php
session_start();
if(!isset($_SESSION['user_id'])){
	header('location:login.php');
}
include './includes/connection.php';
$id = $_SESSION['user_id'];
$p_id=$_GET['id'];
$select = "SELECT * FROM product WHERE product_id='$p_id'";
$data=mysqli_query($con,$select);

while($row= mysqli_fetch_array($data)){
  // $id = $row['product_id'];
  $name = $row['product_name'];
  $rate = $row['product_bought'];
  $stock = $row['product_stock'];
  $image = $row['product_image'];
}

if(isset($_POST['purchase'])){
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $rate = $_POST['rate'];

        if(($quantity > $stock) || ($quantity <= 0)){
            $error = 'Unable to purchase';
        }
        elseif($stock == 0){
            $error = 'Product is not available';
        }
        else{
            $insert_cart ="INSERT INTO purchase_list (user_id,product_name,product_image,product_rate,product_quantity) VALUES ('$id','$name','$image','$rate','$quantity')";
            $query = mysqli_query($con, $insert_cart);

            if($query){
                $update_quantity = $stock - $quantity;
                $update_quantity ="UPDATE product SET product_stock='$update_quantity' WHERE product_id='$p_id' && product_name='$name'";
                $update_query = mysqli_query($con, $update_quantity);
                $_SESSION['sucess'] = "You ordered sucessfully!!";
                header('location:userproduct.php');
            }
            else{
                $error = "Your order did not go through";
            }
        }

}
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
                include 'layouts/admin_menu.php';
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

    <div class="add-form">
      <div class="form-container">
        <form action="" method="POST" enctype="multipart/form-data">
        <div class="title">Order Product</div>
          <p style="color: red;text-align:center;"> <?php if(isset($error)){ echo $error; }?></p>
            <ul class="form-style">
              <li>
                  <label>Product Name</label>
                  <input type="text" name="name" class="field" value="<?php echo $name ;?>" readonly>
              </li>
              <li>
                  <label>Product Image</label>
                  <img src="<?php echo "images/".$image; ?>"  height="70px" width="70px">
                  <input type="hidden" name="image" class="field-img" value="<?php echo "images/".$image; ?>">
              </li>
              <li>
                  <label>Product Rate (R.s)</label>
                  <input type="number" name="rate" class="field" value="<?php  echo $rate; ?>" readonly>
              </li>
              <li>
                  <label>Product Quantity</label>
                  <label style="font-size: 14px;font-weight:200;margin:0;">Availabel Product Quantity : <?php  echo $stock;?></label>
                  <input type="number" name="quantity" class="field"  min="0" oninput="this.value = Math.abs(this.value)">
              </li>
              <li>
                  <input type="submit" value="Order" name="purchase" >
              </li>
          </ul>
        </form>
      </div>
  </div>
  </main>
  <!-- Main Content -->


</section>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="assets\js\dashboard.js"></script>
</body>
</html>