<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit(); // Exit to prevent further code execution
}

include 'includes/connection.php';

// Function to calculate reorder point
function calculateReorderPoint($average_demand_per_day, $lead_time, $safety_stock = 5) {
    return ($average_demand_per_day * $lead_time) + $safety_stock;
}

// Form submission handling
if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $bought = mysqli_real_escape_string($con, $_POST['bought']);
    $stock = mysqli_real_escape_string($con, $_POST['stock']);

    $imagename = $_FILES['image']['name'];
    $tmpname = $_FILES['image']['tmp_name'];
    $img_type = strtolower(pathinfo($imagename, PATHINFO_EXTENSION));
    $destination = "./images/" . $imagename;
    $allow_type = array('png', 'jpeg', 'jpg');
    $imagesize = $_FILES['image']['size'];

    // Validate image
    if (in_array($img_type, $allow_type) && $imagesize <= 2000000) {
        move_uploaded_file($tmpname, $destination);

        // Check if the product already exists
        $select = "SELECT * FROM product WHERE product_name = '$name' ";
        $data = mysqli_query($con, $select);

        while ($row = mysqli_fetch_array($data)) {
            $p_id = $row['product_id'];
            $p_name = $row['product_name'];
            $p_quantity = $row['product_stock'];
        }

        if (mysqli_num_rows($data) > 0) {
            // Update product quantity and reorder point dynamically
            $quantity = $p_quantity + $stock;

            // Calculate the average demand per day (assuming you have this data or can calculate it)
            $average_demand_per_day = $bought / 7; //calculation (using bought quantity over 7 days)
            $lead_time = 2; //  lead time in days

            // Calculate dynamic reorder point
            $reorder_point = calculateReorderPoint($average_demand_per_day, $lead_time);

            $product_quantity = "UPDATE product SET product_stock='$quantity', reorder_point='$reorder_point' WHERE product_name='$name' && product_image='$imagename' && product_bought='$bought'";
            $product_query = mysqli_query($con, $product_quantity);

            // success message for display
            $success_message = "Product updated successfully";
        } else {
            // Insert new product with dynamic reorder point
            $average_demand_per_day = $bought / 7; // calculation (using bought quantity over 7 days)
            $lead_time = 2; // lead time in days

            // Calculate dynamic reorder point
            $reorder_point = calculateReorderPoint($average_demand_per_day, $lead_time);

            $insertquery = "INSERT INTO product(product_name, product_bought, product_stock, product_image, reorder_point) 
                            VALUES('$name','$bought','$stock','$imagename', '$reorder_point')";
            $query = mysqli_query($con, $insertquery);

            // success message for display
            $success_message = "Product added successfully";
        }
    } else {
        $error = "Size exceeded or file type not allowed";
    }
}
?>

<!DOCTYPE html>
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
            if ($_SESSION['user_type'] == 1) {
                include 'layouts/user_menu.php';
            } else {
                include 'layouts/admin_menu.php';
            }
        ?>

        <!-- Main Content -->
        <main>
            <h1 class="title">Product</h1>
            <ul class="breadcrumbs">
                <li><a href="admin_dashboard.php">Home</a></li>
                <li class="divider">/</li>
                <li><a href="product.php" class="active">Product</a></li>
            </ul>
            <div class="add-form">
                <div class="form-container">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="title">Add Product</div>
                        <p style="color: red; text-align:center;"><?php if (isset($error)) { echo $error; } ?></p>
                        <p style="color: green; text-align:center;"><?php echo isset($success_message) ? $success_message : ''; ?></p>
                        <ul class="form-style">
                            <li>
                                <label>Product Name</label>
                                <input type="text" name="name" class="field" required>
                            </li>
                            <li>
                                <label>Product Image</label>
                                <input type="file" name="image" class="field-long" required>
                            </li>
                            <li>
                                <label>Product Rate (R.S)</label>
                                <input type="number" name="bought" class="field" min="0" oninput="this.value = Math.abs(this.value)" required>
                            </li>
                            <li>
                                <label>Product Quantity</label>
                                <input type="number" name="stock" class="field" min="0" oninput="this.value = Math.abs(this.value)" required>
                            </li>
                            <li>
                                <input type="submit" value="Add" name="add">
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </main>
        <!-- Main Content -->
    </section>
          
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="assets/js/dashboard.js"></script>
</body>
</html>
