<?php
    include 'includes/connection.php';

    // Start session to store messages
    session_start();

    // Check if ID is set
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Delete query
        $query = "DELETE FROM users WHERE user_id='$id'";
        $data = mysqli_query($con, $query);

        // Set session message for success or error
        if ($data) {
            $_SESSION['delete_message'] = 'Data Deleted Successfully';
            $_SESSION['delete_status'] = 'success'; // Status for success message
        } else {
            $_SESSION['delete_message'] = 'Error: Unable to delete data';
            $_SESSION['delete_status'] = 'error'; // Status for error message
        }

        // Redirect back to user management page
        header('Location: usermanagement.php');
        exit;
    }
?>
