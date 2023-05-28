<?php
include 'db.php';



// Define the whitelist of allowed origins
$allowedOrigins = array(
    'http://localhost',
);

// Get the request origin
if (array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    $origin = $_SERVER['HTTP_ORIGIN'];
}
else if (array_key_exists('HTTP_REFERER', $_SERVER)) {
    $origin = $_SERVER['HTTP_REFERER'];
} else {
    $origin = $_SERVER['REMOTE_ADDR'];
}

// Check if the origin is in the whitelist
if (in_array($origin, $allowedOrigins)) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Access-Control-Allow-Credentials: true');
    header('Content-Type: application/json');
}

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
} else {
    $user_id = $_SESSION['user_id'];
}

$sql = "SELECT credit_card FROM users WHERE id=:user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$response = array();

if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $response['credit_card'] = $row['credit_card'];
} else {
    $response['error'] = 'No credit card found for this user';
}

echo json_encode($response);
?>
