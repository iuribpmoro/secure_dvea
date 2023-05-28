<!DOCTYPE html>
<html>

<head>
    <title>Product Details</title>
    <link rel="stylesheet" type="text/css" href="styles/product_details.css">
</head>

<body>
    <?php
    include 'db.php';

    
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM products WHERE id=:id LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo '<div class="product_container"><h1>' . $row['name'] . '</h1>';
        echo '<img src="image_getter.php?filename=' . basename($row['image']) . '" width="500">';
        echo '<p>' . $row['description'] . '</p>';
        echo '<p>$' . $row['price'] . '</p>';
        
        $csrf_token = $_SESSION['csrf_token'];
        
        echo '<br><br><form method="POST" action="cart.php">';
        echo '<input type="hidden" name="csrf_token" value="' . $csrf_token . '">';
        echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
        echo '<input type="number" name="quantity" min="1" max="10" value="1">';
        echo '<input type="submit" name="add_to_cart" value="Add to Cart">';
        echo '</form></div>';

        echo '<br><hr><br>';

        echo '<form method="POST" enctype="multipart/form-data" action="product_details.php?id=' . $row['id'] . '">';
        echo '<label>Leave a review:</label><br>';
        echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
        echo '<input type="text" name="name" placeholder="Name"><br>';
        echo '<textarea name="content" placeholder="Review"></textarea><br>';
        echo '<input type="file" name="image"><br><br><br>';
        echo '<input type="submit" name="submit_review" value="Submit Review">';
        echo '</form>';


        if (isset($_POST['submit_review'])) {
            $product_id = $_POST['product_id'];
            $name = $_POST['name'];
            $content = $_POST['content'];

            if (!preg_match('/^[a-zA-Z0-9.!_? @#]+$/', $name)) {
                echo 'Invalid name, please fix it and try again!';
                exit;
            }

            if (!preg_match('/^[a-zA-Z0-9.!_? @#]+$/', $content)) {
                echo 'Invalid review, please fix it and try again!';
                exit;
            }
            
            $image_name = $_FILES['image']['name'] ?? null;
            $image_tmp = $_FILES['image']['tmp_name'] ?? null;
            $image_size = $_FILES['image']['size'] ?? null;
            $image_ext = pathinfo($image_name, PATHINFO_EXTENSION) ?? null;
            $image_filename = pathinfo($image_name, PATHINFO_FILENAME) ?? null;

            if ($image_name) {
                // Validate file name
                $regexPattern = '/^[a-zA-Z0-9]+$/';
                if (!preg_match($regexPattern, $image_filename)) {
                    // Handle invalid file name
                    exit("Invalid file name");
                }

                // Validate file extension
                $allowedExtensions = array('jpg', 'png');
                if (!in_array(strtolower($image_ext), $allowedExtensions)) {
                    // Handle invalid file extension
                    exit("Invalid file extension");
                }

                // Validate file content type
                $allowedContentTypes = array('image/jpeg', 'image/png');
                $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                $uploadedContentType = finfo_file($fileInfo, $image_tmp);
                finfo_close($fileInfo);

                if (!in_array($uploadedContentType, $allowedContentTypes)) {
                    // Handle invalid content type
                    exit("Invalid file content type");
                }

                // Generate new file name
                $newFileName = uniqid('', true) . '.' . $image_ext;
                $image_name = $newFileName;
                move_uploaded_file($image_tmp, 'uploads/' . $image_name);
            }
            
            $sql = "INSERT INTO reviews (name, content, image, product_id) VALUES (:name, :content, :image, :product_id)";
            $stmt = $conn->prepare($sql);
            
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':image', $image_name);
            
            $stmt->execute();
            
            echo '<br>Review submitted successfully';
        }
        

        echo '<br><br><h3>Reviews:</h3>';
        $sql = "SELECT * FROM reviews WHERE product_id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            foreach ($result as $row) {
                echo '<p>Review by ' . htmlspecialchars($row['name']) . '</p>';
                echo '<p>' . htmlspecialchars($row['content']) . '</p>';
                if ($row['image']) {
                    echo '<img src="/secure_dvea/uploads/' . htmlspecialchars($row['image']) . '" width="200">';
                }
                echo '<br><br>';
            }
        } else {
            echo "No reviews to display";
        }


    } else {
        echo "Invalid product ID";
    }
    ?>
    <br>
    <a href="index.php" class="go-back">Go Back</a>
</body>

</html>