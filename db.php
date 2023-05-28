<?php

    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    $servername = "localhost";
    $username = "lab_user";
    $password = "";
    $dbname = "shop";

    // Create connection
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
    
?>