<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hardware Store - Cart</title>
    
    <style>
        
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
        
    </style>
</head>
<body>
    <header>
        <h1>Hardware Store - Cart</h1>
    </header>
    <div class="container">
        <h2>Cart</h2>
        <div class="cart">
            <?php if (empty($_SESSION['cart'])): ?>
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
                        <?php
                        // Fetch details for each product in the cart
                        foreach ($_SESSION['cart'] as $item) {
                            $productId = $item['productId'];
                            $quantity = $item['quantity'];
                            
                            // Query to fetch product details
                            $sql = "SELECT p.id, p.name, p.price FROM Products p WHERE p.id = :productId";
                            //$sql = SELECT * FROM Carts;
                            $stmt = $conn->prepare($sql);
                            $stmt->bindParam(':productId', $productId);
                            $stmt->execute();
                            $product = $stmt->fetch(PDO::FETCH_ASSOC);
                            
                            // Display product details
                            echo "<tr>";
                            echo "<td>" . $product['id'] . "</td>";
                            echo "<td>" . $product['name'] . "</td>";
                            echo "<td>$" . $product['price'] . "</td>";
                            echo "<td>" . $quantity . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <a href="shop.php" class="button">Back to Shop</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
