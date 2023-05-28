<!DOCTYPE html>
<html>

<head>
    <title>Product Listing</title>
</head>

<body>
    <h1>Product Listing</h1>
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
            $search = $_GET['search'];
            $sql = "SELECT * FROM products WHERE name LIKE '%$search%'";
        } else {
            $sql = "SELECT * FROM products";
        }

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<li><a href="product_details.php?id=' . $row['id'] . '">' . $row['name'] . '</a></li>';
            }
        } else {
            echo "No products to display";
        }
        ?>
    </ul>
    <br>
    <a href="cart.php">View Cart</a>
</body>

</html>