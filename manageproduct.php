<!DOCTYPE html>
<?php 
    session_start();
    if(!isset($_SESSION['user_id'])){
        header('location:login.php');
    }
?>
<?php
    include 'includes/connection.php';

    // Check if search term is provided
    if (isset($_GET['search'])) {
        $searchTerm = mysqli_real_escape_string($con, $_GET['search']);
        $query = "SELECT * FROM product WHERE product_name LIKE '%$searchTerm%' ORDER BY product_id DESC";
    } else {
        $query = "SELECT * FROM product ORDER BY product_id DESC";
    }
    $result = mysqli_query($con, $query);

    // Handle Reorder Point Calculation Button click
    if (isset($_POST['reorder_point_btn'])) {
        // Fetch all products
        $select_all_products = "SELECT * FROM product";
        $product_result = mysqli_query($con, $select_all_products);

        while ($row = mysqli_fetch_array($product_result)) {
            $product_id = $row['product_id'];
            $product_name = $row['product_name'];
            $total_bought = $row['product_bought'];
            $stock = $row['product_stock'];

            // Set lead time and safety stock (These values can be dynamic if needed)
            $lead_time = 7; //  7 days lead time
            $safety_stock = 5; //  5 units safety stock

            // Calculate Average Demand Per Day
            $average_demand_per_day = $total_bought / 30; // Assuming a 30-day period

            // Calculate Reorder Point
            $reorder_point = ($average_demand_per_day * $lead_time) + $safety_stock;

            // Update the reorder point in the database
            $update_query = "UPDATE product SET reorder_point = '$reorder_point' WHERE product_id = '$product_id'";
            mysqli_query($con, $update_query);
        }
        $success_message = "Reorder points recalculated successfully!";
    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stationery Management System</title>

    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/add.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        /* Modal Styles */
        .search-container {
            margin-bottom: 0;
            text-align: center;
        }
        .search-box {
            padding: 8px 15px;
            font-size: 16px;
            width: 30%;
        }
        .modal {
            display: none;
            position: fixed;
            top: 20%;
            left: 12%;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            width: 300px;
            margin: auto;
            animation: fadeIn 0.3s ease-in-out;
        }
        .close-btn {
            float: right;
            font-size: 1.5em;
            cursor: pointer;
        }
        .confirm-btn, .cancel-btn {
            padding: 10px 20px;
            margin: 10px;
            cursor: pointer;
        }
        .confirm-btn {
            background-color: red;
            color: white;
        }
        .cancel-btn {
            background-color: grey;
            color: white;
        }
    </style>
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

    <!-- Main Content -->
    <main >
        <h1 class="title">Product</h1>
        <ul class="breadcrumbs">
            <li><a href="admin_dashboard.php">Home</a></li>
            <li class="divider">/</li>
            <li><a href="product.php" class="active">Product</a></li>
        </ul>
        <div class="content">
            <div>
            <!-- Add Product Button -->
            <section class="modala" style="display: flex; justify-content: space-between; margin-top: 20px; text-align: center;">
                <button class="modal__button">
                    <a href="addproduct.php"> Add Product </a>
                </button>

                <form method="POST" style="display: inline-block; margin-left: 20px;">
                    <button type="submit" name="reorder_point_btn" class="modal__button">Recalculate Reorder Points</button>
                </form>
                <?php if (isset($success_message)) { echo "<p style='color: green;'>$success_message</p>"; } ?>
            </section>
            </div>
            
            <div class="table">
                <section class="table_body">
                    <table id="table" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Image</th>
                                <th>Rate (R.S)</th>
                                <th>Quantity</th>
                                <th>Product Added</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $query = "SELECT * FROM product ORDER BY product_id DESC";
                                $result = mysqli_query($con,$query);

                                while($row= mysqli_fetch_array($result)){
                                    $id = $row['product_id'];
                                    $name = $row['product_name'];
                                    $bought = $row['product_bought'];
                                    $stock = $row['product_stock'];
                                    $image = $row['product_image'];
                                    $time = $row['product_add_date'];
                            ?>
                                <tr>
                                    <td><?php  echo $name?></td>
                                    <td><img src="<?php echo "images/".$row['product_image']; ?>" alt="image" width="100px" height="100px"></td>
                                    <td><?php  echo $bought ?></td>
                                    <td><?php  echo $stock ?></td>
                                    <td><?php  echo $time ?></td>
                                    <td>
                                        <a href="./updateproduct.php?id=<?php echo $id; ?>"><i class='bx bx-edit' style="font-size:25px;color:green;"></i></a>
                                        <a href="#" class="delete-link" data-id="<?php echo $id; ?>"><i class='bx bxs-message-square-x' style="font-size:25px;color:red;"></i></a>
                                    </td>
                                </tr>
                            <?php
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

    <!-- Confirmation Modal for Deletion -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h3>Are you sure you want to delete this product?</h3>
            <form action="deleteproduct.php" method="GET">
                <input type="hidden" name="id" id="productId">
                <button type="submit" class="confirm-btn">Yes, Delete</button>
                <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="assets/js/dashboard.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#table').DataTable();
        });

        // Function to open the delete confirmation modal
        function openModal(productId) {
            document.getElementById('productId').value = productId;
            document.getElementById('deleteModal').style.display = 'block';
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        // Attach event listener to delete buttons
        document.querySelectorAll('.delete-link').forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                openModal(this.getAttribute('data-id'));
            });
        });

        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target == document.getElementById('deleteModal')) {
                closeModal();
            }
        };

        // Search functionality
        $(document).ready(function(){
            var table = $('#table').DataTable();
            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();  // Apply the search term to the DataTable
            });
        });
    </script>

</body>
</html>
