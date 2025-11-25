<!DOCTYPE html>
<?php 
    session_start();
    if(!isset($_SESSION['user_id'])){
        header('location:login.php');
    }
	include 'includes/connection.php';
    $id = $_SESSION['user_id'];
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
    
<!-- Main Content -->
<main>
    <h1 class="title">Sales Report</h1>
    <ul class="breadcrumbs">
        <li><a href="user_dashboard.php">Home</a></li>
        <li class="divider">/</li>
        <li><a href="userproduct.php" class="active">Report</a></li>
    </ul>
    <div class="content">
        <form action="" method="GET">
            <div class="title" style="text-align:center;">Generate Report </div>
            <p style="color: red;text-align:center;"> <?php if(isset($error)){ echo $error; }?></p>
            <ul class="form-style">
                <li>
                    <label>Start Date</label>
                    <input type="date" name="from" class="field">
                </li>
                <li>
                    <label>End Date</label>
                    <input type="date" name="to" class="field">
                </li>
                <li style="margin-bottom:5px;">
                    <input type="submit" value="Generate" name="generate">
                </li>
            </ul>
        </form>

        <?php
        $showPdfButton = false; // Variable to track whether to show the PDF button

        if (isset($_GET['generate'])) {
            $from = $_GET['from'];
            $to = $_GET['to'];

            include 'includes/connection.php';

            $query = "SELECT * FROM purchase_list WHERE purchase_date BETWEEN '$from' AND '$to'";
            $query_run = mysqli_query($con, $query);

            if (mysqli_num_rows($query_run) > 0) {
                $showPdfButton = true; // Set the variable to true if there is data
            }
        }
        ?>

        <div class="table">
            <section class="table_body">
                <table id="table" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Product Name</th>
                            <th>Product Rate(R.s)</th>
                            <th>Product Quantity</th>
                            <th>Purchase Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(isset($_GET['generate'])){
                        $from=$_GET['from'];
                        $to=$_GET['to'];

                        include 'includes/connection.php';

                        $query = "SELECT * from purchase_list where purchase_date between '$from' and '$to'";
                        $query_run = mysqli_query($con, $query);
                        if(mysqli_num_rows($query_run) > 0){
                            while( $fetch_purchase = mysqli_fetch_assoc($query_run)){
                                $p_id= $fetch_purchase['purchase_id'];
                                $id= $fetch_purchase['user_id'];  
                    ?>
                    <tr>
                        <td>
                        <?php
                            $user = mysqli_query($con, "SELECT * FROM users  WHERE user_id='$id'");
                            if(mysqli_num_rows($user) > 0){
                                while( $fetch_name = mysqli_fetch_assoc($user)){
                        ?>
                                    <?php  echo $fetch_name['username']?>
                        <?php } }?>
                        </td>
                        <td><?php  echo $fetch_purchase['product_name']?></td>
                        <td><?php  echo $fetch_purchase['product_quantity'] ?></td>
                        <td><?php  echo $fetch_purchase['product_rate'] ?></td>
                        <td><?php echo $fetch_purchase['purchase_date'] ?></td>
                    </tr>
                    <?php
                            }
                        }
                        else{
                            $error="No records found";
                        }
                    }      
                    ?>
                    </tbody>
                </table>
            </section>
            <?php if ($showPdfButton): ?>
                <button id="pdfButton" onclick="downloadPDF()" style="background-color: #04AA6D;border: none;color: white;padding: 10px 20px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;">Download PDF</button>
            <?php endif; ?>
        </div>
    </div>
</main>
</section>

<script src="assets\js\dashboard.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function(){
        $('#table').DataTable();
    });
</script>

<script>
    function downloadPDF() {
        // Redirect to report.php when the button is clicked
        window.location.href = "report.php?from=<?php echo isset($_GET['from']) ? $_GET['from'] : ''; ?>&to=<?php echo isset($_GET['to']) ? $_GET['to'] : ''; ?>";
    }
</script>
</body>
</html>
