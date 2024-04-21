<?php

// Include your database connection file here
require_once('Handlers/dbh.inc.php');

// Check if the logout button is clicked


// Fetch products from the database
$productsQuery = "SELECT iv.id,
    storeId,
    p.id AS productId,
    productCount,
    p.name,
    p.price,
    p.description,
    p.picture
FROM Inventory iv
JOIN Products p ON iv.productId = p.id";
$productsStmt = $conn->query($productsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hardware Store - Shop</title>
    <!-- You can include CSS stylesheets here if needed -->
    <style>
        /* Add your CSS styles here */
        /* Ensure your CSS styles are included here */
    </style>
</head>
<body>
    <header>
        <h1>Hardware Store - Shop</h1>
    </header>
    <div class="container">
        <h2>Products</h2>
        <div class="products">
            <?php
            
                while ($row = $productsStmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='product'>
                            <h3>Product Name: " . $row['name'] . "</h3>
                            <p>Description: " . $row['description'] . "</p>
                            <p>Price: $" . $row['price'] . "</p>
                            <form action='Handlers/ShopHandler.inc.php' method='post'>
                                <label for='quantity'>Quantity:</label>
                                <input type='number' id='quantity' name='quantity' min='1' value='1'>
                                <input type='hidden' name='productId' value='" . $row['id'] . "'>
                                <button type='submit' name='submit'>Add to Cart</button>
                            </form>
                        </div>";
                    }
            
            ?>
            <a href="Cart.php" class="cart-button">View Cart</a>
        </div>
    </div>
    <?php
        echo "<div>UserId: " . $_COOKIE['userId'] . "</div>";
    ?>
    <!-- Logout form -->
    <form method="post" action="">
        <button type="submit" name="logout">Logout</button>
    </form>
    <?php
    if (isset($_POST['logout'])) {
        // Remove the userId cookie
        setcookie('userId', '', time() - 3600, '/'); // Expire the cookie
        // Redirect to the login page or any other desired page
        header("Location: login.php"); // Replace 'login.php' with your desired page
        exit(); // Terminate the script
    }
    ?>
</body>
</html>
