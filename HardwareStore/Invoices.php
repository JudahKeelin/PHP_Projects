<?php
    // Check if a user is logged in
    if (!isset($_COOKIE['userId'])) {
        // Redirect to login page or handle authentication
        header("Location: login.php");
        exit();
    }

    // Include your database connection file here
    require_once('Handlers/dbh.inc.php');

    $userId = $_COOKIE['userId'];
    $userLevel = $_COOKIE['userLevel'];

    // Fetch invoices from the database
    $getInvoicesQuery = "SELECT ivo.id,
                                ivo.timestamp,
                                ivo.invoiceId,
                                ivo.userId,
                                ivo.status,
                                ivo.storeId,
                                hs.name AS storeName,
                                ivo.inventoryId,
                                pd.id AS productId,
                                pd.name,
                                ivo.productCount,
                                CAST(pd.price AS DECIMAL(10, 2)) AS price,
                                hs.managerId
                            FROM Invoices ivo
                            JOIN (
                            SELECT nve1.id, nve1.productId 
                            FROM Inventory nve1
                            ) nve ON ivo.inventoryId = nve.id
                            JOIN (
                            SELECT pd1.id, pd1.name, pd1.price
                            FROM Products pd1
                            ) pd ON pd.id = nve.productId
                            JOIN (
                            SELECT hs1.id, hs1.name, hs1.managerId
                            FROM HardwareStores hs1
                            ) hs ON hs.id = ivo.storeId
                            WHERE (
                            :userLevel = 0
                            ) OR (
                            :userLevel = 1 AND hs.managerId = :userId
                            ) OR (
                            :userLevel = 2 AND ivo.userId = :userId
                            )";

    $getInvoicesStmt = $conn->query($getInvoicesQuery);
    $getInvoicesStmt->bindParam(':userId', $_COOKIE['userId']);
    $getInvoicesStmt->bindParam(':userLevel', $_COOKIE['userLevel']);
    $getInvoicesStmt->execute();
    


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
        header form {
            float: left;
            margin-left: 2rem;
            margin-top: 2rem;
            margin-right: -4rem
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

        /* Add more styles as needed */
    </style>
</head>
<body>
    <header>
        <!-- Logout form -->
        <form method="post" action="">
            <button type="submit" name="logout">Logout</button>
        </form>
        <h1>Hardware Store - Cart</h1>
    </header>
    <div class="container">
        <h2>Cart</h2>
        <div class="cart">
            <?php if (empty($cartItems)): ?>
                <p>Your cart is empty.</p>
                <a href="shop.php" class="button" style="display: inline-block">Back to Shop</a>
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
                <a href="shop.php" class="button" style="display: inline-block">Back to Shop</a>
            <?php endif; ?>
        </div>
        
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