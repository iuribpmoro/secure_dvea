<?php

    // Check if the user is authenticated
    if (!isset($_SESSION['user_id'])) {
        // If the user is not authenticated, redirect to the login page
        header("Location: login.php");
        exit();
    }
?>
