<?php

// Check if a user is logged in
if (!isset($_COOKIE['userId'])) {
    // Redirect to login page or handle authentication
    header("Location: login.php");
    exit();
}

// Include your database connection file here
require_once('Handlers/dbh.inc.php');



// Fetch products from the database
$productsQuery = "SELECT iv.id,
    storeId,
    p.id AS productId,
    productCount,
    p.name,
    CAST(p.price AS DECIMAL(10, 2)) AS price,
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
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
        .container {
            max-width: 960px;
            margin: 20px auto;
            padding: 0 20px;
        }
        .products {
            display: flex;
            flex-wrap: wrap;
            justify-content: left;
        }
        .product {
            display: inline-block;
            width: 25rem;
            height: 15rem;
            margin: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        header form {
            float: left;
            margin-left: 2rem;
            margin-top: 2rem;
            margin-right: -4rem
        }
    </style>
</head>
<body>
    <header>
        <!-- Logout form -->
        <form method="post" action="">
            <button type="submit" name="logout">Logout</button>
        </form>
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
                            <p>Available Quantity: " . $row['productCount'] . "</p>
                            <p>Price: $" . $row['price'] . "</p>
                            <form action='Handlers/ShopHandler.inc.php' method='post'>
                                <label for='quantity'>Quantity:</label>
                                <input type='number' id='quantity' name='quantity' min='1' max='" . $row['productCount'] . "' value='1'>
                                <input type='hidden' name='inventoryId' value='" . $row['id'] . "'>
                                <button type='submit' name='submit'>Add to Cart</button>
                            </form>
                        </div>";
                    }
            ?>
        </div>
        <a href="Cart.php" class="cart-button" style="display: block; padding-top: 15px">View Cart</a>
    </div>
    <?php
    if (isset($_POST['logout'])) {
        // Remove the userId cookie
        setcookie('userId', '', time() - 3600, '/'); // Expire the cookie
        setcookie('userLevel', '', time() - 3600, '/'); // Expire the cookie
        // Redirect to the login page or any other desired page
        header("Location: login.php"); // Replace 'login.php' with your desired page
        exit(); // Terminate the script
    }
    ?>
</body>
</html>
