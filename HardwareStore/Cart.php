<?php

// Include your database connection file here
require_once('Handlers/dbh.inc.php');

// Check if a user is logged in
if (!isset($_COOKIE['userId'])) {
    // Redirect to login page or handle authentication
    header("Location: login.php");
    exit();
}

// Fetch cart items from the database
//$userId = $_SESSION['userId'];
$cartItemsQuery = "SELECT c.*, p.name, p.price FROM Carts c
                   JOIN Products p ON c.productId = p.id
                   WHERE c.userId = :userId";
$cartItemsStmt = $conn->prepare($cartItemsQuery);
$cartItemsStmt -> bindParam(':userId', $_COOKIE['userId']);
$cartItemsStmt -> execute();
$cartItems = $cartItemsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hardware Store - Cart</title>
    <!-- You can include CSS stylesheets here if needed -->
    <style>
        /* Add your CSS styles here */
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
        /* Add more styles as needed */
    </style>
</head>
<body>
    <header>
        <h1>Hardware Store - Cart</h1>
    </header>
    <div class="container">
        <h2>Cart</h2>
        <div class="cart">
            <?php if (empty($cartItems)): ?>
                <p>Your cart is empty.</p>
                <a href="shop.php" class="button">Back to Shop</a>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td><?php echo $item['productId']; ?></td>
                                <td><?php echo $item['name']; ?></td>
                                <td>$<?php echo $item['price']; ?></td>
                                <td><?php echo $item['productCount']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="shop.php" class="button">Back to Shop</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
