<!DOCTYPE html>
<html>

<head>
    <title>Footprints</title>
    <link rel="stylesheet" type="text/css" href="styles/index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
            // Create a new XMLHttpRequest object
            var xhr = new XMLHttpRequest();
            // Define the URL of the API
            var url = 'card_api.php';
            // Configure the request
            xhr.open('GET', url, true);
            xhr.responseType = 'json';

            // Define the event handler for successful response
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var data = xhr.response;

                    if (data.credit_card) {
                        document.getElementById('credit-card').textContent = 'Credit card: ' + data.credit_card;
                    } else {
                        document.getElementById('credit-card').textContent = 'No credit card on file';
                    }
                }
            };

            // Send the request
            xhr.send();
        });

	</script>
</head>

<body>
    <img src="./images/logo.png" />
    <h1>Welcome to Footprints, the shoe store where every step counts!</h1>
    <p>Your default payment method:</p>
    <div id="credit-card"></div>
    
    <form method="GET">
        <label>Search:</label>
        <input type="text" name="search">
        <input type="submit" value="Search">
    </form>
    <br>
        
    <ul>
        <?php
            include 'db.php';

            if (isset($_GET['search'])) {
                $search = trim($_GET['search']);
                if (!preg_match('/^[a-zA-Z0-9]+$/', $search)) {
                    echo 'Invalid search parameter';
                    exit;
                }
                $search = '%' . $search . '%';
                $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE :search");
                $stmt->bindParam(':search', $search);
            } else {
                $stmt = $conn->prepare("SELECT * FROM products");
            }
            
            
            $stmt->execute();
            $result = $stmt->fetchAll();
            
            if (count($result) > 0) {
                foreach ($result as $row) {
                    echo '<li><a href="product_details.php?id=' . $row['id'] . '">' . $row['name'] . '</a></li>';
                }
            } else {
                echo "No products to display";
            }
        ?>
    </ul>
    <br>
    <br>
    <?php
        echo "<a href='order_history.php?user_id=" . $_SESSION['user_id'] . "'>My Order History</a>";
    ?>

    <br><br>
    <a href="logout.php">Logout</a>
    
</body>

</html>