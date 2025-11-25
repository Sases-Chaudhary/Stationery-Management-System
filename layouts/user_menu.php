
<a href="user_dashboard.php" class="brand"><img alt="logo" class="logo" src="assets/images/logo.png"><img alt="logo" class="logo" src="assets/images/brand.png"></a>
<ul class="side-menu">
    <li><a href="user_dashboard.php" class="active"><i class='bx bxs-dashboard icon'></i> Dashboard</a></li>
    <li>
        <a href="userproduct.php"><i class='bx bxs-doughnut-chart icon'></i> Product</a>
    </li>
    <li>
        <a href="purchase_list.php"><i class='bx bxs-doughnut-chart icon'></i> Order List</a>
    </li>
    <li class="divider"></li>
    <li><a href="profile.php"><i class='bx bxs-cog icon'></i> Profile </a></li>
    <li><a href="logout.php"><i class='bx bxs-log-out-circle icon'></i> Logout </a></li>
</ul>
</section>
<!-- SIDEBAR -->
<!-- NAVBAR -->
<section id="content">
    <!-- NAVBAR -->
    <nav>
        <i class='bx bx-menu toggle-sidebar'></i>
        <form action="#">
            <div class="form-group">
                <div class="date">
                    <?php
                        echo date('d-m-y , l');
                    ?>
                </div>
            </div>
        </form>
        <span class="divider"></span>
        <div class="profile">
            <img src="assets\images\user.png" alt="User">

            <ul class="profile-link">
                <li><a href="profile.php"><i class='bx bxs-user-circle icon'></i> Profile</a></li>
                <li><a href="logout.php"><i class='bx bxs-log-out-circle'></i> Logout</a></li>
            </ul>
        </div>
    </nav>