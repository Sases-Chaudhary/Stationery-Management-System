<?php
include 'includes/connection.php';

function updateReorderPoint($con) {
    // Query to get all products and calculate reorder points
    $query = "SELECT product_id, product_name, product_bought, product_stock FROM product";
    $result = $con->query($query);
    
    while ($product = $result->fetch_assoc()) {
        // Reorder Point = (average demand per month) * (lead time in months)
        $demand_per_month = $product['product_bought'] / 30;  //assume `product_bought` is total sales over 30 days
        $lead_time = 2;  //Assume a 2-month lead time
        
        $reorder_point = $demand_per_month * $lead_time;

        // Update the reorder point in the database for this product
        $update_query = "UPDATE product SET reorder_point = $reorder_point WHERE product_id = {$product['product_id']}";
        
        if ($con->query($update_query) === TRUE) {
            echo "Reorder point updated successfully for {$product['product_name']}<br>";
        } else {
            echo "Error updating reorder point for {$product['product_name']}: " . $con->error . "<br>";
        }
    }
}

updateReorderPoint($con);
?>
