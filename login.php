<?php

// Include the database connection
include 'db.php';

// Check if the username and password were submitted
if(isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL query to check if the username and password are correct
    $stmt = $conn->prepare('SELECT id FROM users WHERE username=:username AND password=:password');
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);

    // Execute the query and fetch the result
    $stmt->execute();
    $result = $stmt->fetchAll();

    // If a row is returned, the login is successful
    if(count($result) > 0) {
        // Fetch the user's data
        $row = $result[0];

        // Set the session variable to indicate that the user is logged in
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $row['id'];

        // Redirect to the home page or another protected page
        header('Location: index.php');
        exit();
    } else {
        // If no rows are returned, the login is unsuccessful
        echo 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/login.css">
    <title>Login</title>
</head>
<body>
<img src="./images/logo.png" />
    <form method="post" action="login.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br><br>
        <input type="submit" value="Login">
        <?php if(isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
    </form>
</body>
</html>

