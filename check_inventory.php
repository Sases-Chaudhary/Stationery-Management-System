<?php
include './includes/connection.php';

function checkInventoryLevels($con) {
    echo "<div class='alert-container'>";
    $query = "SELECT * FROM product";
    $result = $con->query($query);

    while ($product = $result->fetch_assoc()) {
        if ($product['product_stock'] <= $product['reorder_point']) {
            echo "<div class='alert'>Reorder needed for " . $product['product_name'] . ".
                  <button class='close-btn'>&times;</button>
                  </div>";
        }
    }
    echo "</div>";
}

checkInventoryLevels($con);
?>
