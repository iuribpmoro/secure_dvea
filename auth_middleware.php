<?php

// Check if a session is already active
if (session_status() === PHP_SESSION_NONE) {
    // No session active, start a new one
    session_start();
}

// Define the pages that should bypass the authorization check
$whitelist = array(
    '/secure_dvea/login.php',
    '/secure_dvea/db.php'
    // Add more whitelisted pages here
);

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Get the current page
$current_page = $_SERVER['PHP_SELF'];

// Check if the current page is in the whitelist
if (!in_array($current_page, $whitelist)) {
    // Perform your authorization check here
    // If the user is not authorized, redirect them to the login page or show an unauthorized access message
    if (!isset($_SESSION['user_id'])) {
        // If the user is not authenticated, redirect to the login page
        header("Location: login.php");
        exit();
    }
}