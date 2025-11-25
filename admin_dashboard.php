<!DOCTYPE html>
<?php
// Start the session
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit(); // Exit to prevent further code execution
}

// Include database connection file
include './includes/connection.php';

// Get the user ID from the session
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
        // Check user type and include the appropriate menu
        if ($_SESSION['user_type'] == 1) {
            header('location:user_dashboard.php');
        } else {
            include 'layouts/admin_menu.php';
        }
        ?>

        <!-- Main Content -->
        <main>
            <h1 class="title">Dashboard</h1>
            <ul class="breadcrumbs">
                <li><a href="admin_dashboard.php">Home</a></li>
                <li class="divider">/</li>
                <li><a href="admin_dashboard.php" class="active">dashboard</a></li>
            </ul>
            <div>
                <?php  include 'check_inventory.php'; ?>
            </div>
            <div class="info-data">
                <!-- User Card -->
                <div class="card">
                    <div class="head">
                        <div class="items">
                            <h2>Users</h2>
                            <?php
                            // Count the number of users
                            $check_user = "SELECT * FROM users ";
                            $query_user = mysqli_query($con, $check_user);
                            $row = mysqli_num_rows($query_user);
                            ?>
                            <p><?php echo $row; ?></p>
                        </div>
                        <i class='bx bx-user' style="color:blue;"></i>
                    </div>
                </div>

                <!-- Product Card -->
                <div class="card">
                    <div class="head">
                        <div>
                            <h2>Products</h2>
                            <?php
                            // Count the number of products
                            $check_product = "SELECT * FROM product ";
                            $query_product = mysqli_query($con, $check_product);
                            $row_product = mysqli_num_rows($query_product);

                            // Count the number of sales
                            $check_sales = "SELECT * FROM purchase_list ";
                            $query_sales = mysqli_query($con, $check_sales);
                            $row_sales = mysqli_num_rows($query_sales);

                            // Calculate total sales quantity
                            $fetch_quantity = mysqli_fetch_assoc($query_sales);
                            $n = $row_sales;
                            $sales = 0;
                            for ($a = 1; $a <= $n; $a++) {
                                $sales = $sales + $fetch_quantity['product_quantity'];
                            }
                            ?>
                            <p><?php echo $row_product; ?></p>
                        </div>
                        <i class='bx bxs-doughnut-chart icon' style="color:green;"></i>
                    </div>
                </div>

                <!-- Sales Card -->
                <div class="card">
                    <div class="head">
                        <div>
                            <h2>Sales</h2>
                            <p><?php echo $sales; ?></p>
                        </div>
                        <i class='bx bxs-chart icon' style="color:purple;"></i>
                    </div>
                </div>
            </div>

            <div class="data">
                <div class="content-data">
                    <div class="head">
                        <h3>Recent Orders</h3>
                    </div>
                    <div class="table">
                        <section class="table_body">
                            <table>
                                <?php
                                // Include database connection file
                                include 'includes/connection.php';
                                ?>
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Product Added</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Select recent orders from purchase_list table
                                    $select_product = mysqli_query($con, "SELECT * FROM purchase_list  ORDER BY purchase_date DESC LIMIT 7");
                                    if (mysqli_num_rows($select_product) > 0) {
                                        while ($fetch_purchase = mysqli_fetch_assoc($select_product)) {
                                            $p_id = $fetch_purchase['purchase_id'];
                                    ?>
                                            <tr>
                                                <td><?php echo $fetch_purchase['product_name'] ?></td>
                                                <td><?php echo $fetch_purchase['product_quantity'] ?></td>
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
            </div>
        </main>
        <!-- Main Content -->
    </section>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="assets\js\dashboard.js"></script>
</body>

</html>
