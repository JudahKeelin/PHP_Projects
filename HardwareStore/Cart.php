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
$cartItemsQuery = "SELECT c.id, c.inventoryId, c.productCount, i.name, CAST(i.price AS DECIMAL(10, 2)) AS price
                    FROM Carts c
                    JOIN Inventory i ON c.inventoryId = i.id
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
        .headerClass {
            float: left;
            margin-left: 2rem;
            margin-top: 2rem;
            margin-right: -6rem
        }
        .container {
            max-width: 960px;
            margin: 20px auto;
            padding: 0 20px;
        }
        tbody tr:nth-child(even){
            background-color: #f2f2f2;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #333;
            color: white;
            text-align: left;
        }
        tb {
            align-items: left;
        }
        h1 {
            text-align: left;
            margin-left: 45rem;
        }
        /* Add more styles as needed */
    </style>
</head>
<body>
    <header>
        <!-- Logout form -->
        <form method="post" action="" class="headerClass">
            <button type="submit" name="logout">Logout</button>
        </form>
        <button onclick="window.location.href='shop.php'" class="headerClass" style="margin-left: 7rem">Back to Shop</button>
        <h1>Hardware Store - Cart</h1>
    </header>
    <div class="container">
        <h2>Cart</h2>
        <div class="cart">
            <?php if (empty($cartItems)): ?>
                <p>Your cart is empty.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td><?php echo $item['inventoryId']; ?></td>
                                <td><?php echo $item['name']; ?></td>
                                <td>$<?php echo $item['price']; ?></td>
                                <td><?php echo $item['productCount']; ?></td>
                                <td>$<?php echo number_format($item['price'] * $item['productCount'], 2); ?></td>
                                <td>
                                    <form action="Handlers/DeleteCartItemHandler.inc.php" method="post">
                                        <input type="hidden" name="cartItemId" value="<?php echo $item['id']; ?>">
                                        <button type="submit" name="submit">Delete</button>
                                    </form>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <br>
                <form action="Handlers/CheckoutHandler.inc.php" method="post" style="margin-right: 10px; display: inline-block">
                    <button type="submit" name="checkout">Checkout</button>
                </form>
            <?php endif; ?>
        </div>
        
    </div>
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
