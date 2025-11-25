<!DOCTYPE html>
<?php 
    session_start();
    if(!isset($_SESSION['username'])){
        header('location:login.php');
    }
?>
<?php
    include 'includes/connection.php';

    if(isset($_POST['add'])){
        $name = $_POST['name'];
        $bought = $_POST['bought'];
        $sold = $_POST['sold'];
        $stock = $_POST['stock'];

        $imagename = $_FILES['image']['name'];
        $tmpname = $_FILES['image']['tmp_name'];
        $img_type = strtolower(pathinfo($imagename, PATHINFO_EXTENSION));
        $destination = "./images/" . $imagename;
        $allow_type = array('png', 'jpeg', 'jpg');
        $imagesize = $_FILES['image']['size'];

        if(in_array($img_type, $allow_type)){
            if($imagesize <= 2000000){
                move_uploaded_file($tmpname, $destination);
                $insertquery = "INSERT INTO product(product_name, product_bought, product_sold, product_stock, product_image) VALUES('{$name}', '{$bought}', '{$sold}', '{$stock}', '$imagename')";
                $query = mysqli_query($con, $insertquery);
            } else {
                echo "Size exceeded";
            }
        } else {
            echo "File type not allowed";
        }
    }

    // Check if search term is provided
    if (isset($_GET['search'])) {
        $searchTerm = mysqli_real_escape_string($con, $_GET['search']);
        // SQL query with LIKE operator for searching product name
        $query = "SELECT * FROM users WHERE username LIKE '%$searchTerm%' =";
    } else {
        // If no search term, show all products
        $query = "SELECT * FROM users ORDER BY user_id DESC";
    }
    $result = mysqli_query($con, $query);

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
        .search-container {
            margin-bottom: 0;
            text-align: center;
        }
        .search-box {
            padding: 8px 15px;
            font-size: 16px;
            width: 30%;
        }
        /* Modal Styles */
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
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Notification Styles */
        .notification {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            z-index: 9999;
            animation: fadeIn 0.5s ease-out;
        }
        .notification.error {
            background-color: #dc3545;
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
            } else {
                include 'layouts/admin_menu.php';
            }
        ?>

        <!-- Main Content -->
        <main>
            <h1 class="title">User Management</h1>
            <ul class="breadcrumbs">
                <li><a href="admin_dashboard.php">Home</a></li>
                <li class="divider">/</li>
                <li><a href="usermanagement.php" class="active">User Management</a></li>
            </ul>
            <div class="content">
                <div class="table">
                    <section class="table_body">
                        <table id="table" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>User Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $query = "SELECT * FROM users WHERE user_type='1'";
                                    $result = mysqli_query($con, $query);
                                    
                                    while($row = mysqli_fetch_array($result)){
                                        $id = $row['user_id'];
                                        $username = $row['username'];
                                        $email = $row['email'];
                                        $type = $row['user_type'];
                                ?>
                                    <tr>
                                        <td><?php echo $username; ?></td>
                                        <td><?php echo $email; ?></td>
                                        <td><?php echo $type == 1 ? "User" : "Admin"; ?></td>
                                        <td>
                                            <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $id; ?>)">
                                                <i class='bx bxs-message-square-x' style="font-size:25px;color:red;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>

            <!-- Confirmation Modal -->
            <div id="confirmModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" onclick="closeModal()">&times;</span>
                    <h2>Are you sure you want to delete this user?</h2>
                    <button id="confirmDeleteBtn" class="confirm-btn">Yes</button>
                    <button class="cancel-btn" onclick="closeModal()">No</button>
                </div>
            </div>

            <!-- Notification -->
            <div id="notification" class="notification"></div>

        </main>
    </section>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="assets/js/dashboard.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#table').DataTable({
            });
        });

        let deleteUserId = null;

        function confirmDelete(id) {
            deleteUserId = id;
            document.getElementById('confirmModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('confirmModal').style.display = 'none';
        }

        document.getElementById('confirmDeleteBtn').onclick = function () {
            if (deleteUserId) {
                window.location.href = "./deleteuser.php?id=" + deleteUserId;
            }
        };
        
        $(document).ready(function(){
            var table = $('#table').DataTable();

            // Event listener for search input
            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();  // Apply the search term to the DataTable
            });
        });

    </script>

</body>
</html>
