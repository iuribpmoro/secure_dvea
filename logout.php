<?php
// unset all session variables
session_unset();

// destroy the session
session_destroy();

// redirect the user to the login page
header("Location: login.php");
exit;
?>
