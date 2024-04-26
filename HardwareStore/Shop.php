<?php
// Check if a user is logged in
if (!isset($_COOKIE['userId'])) {
    // Redirect to login page or handle authentication
    header("Location: login.php");
    exit();
}

// Include your database connection file here
require_once('Handlers/dbh.inc.php');
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    // Remove the userId and userLevel cookies
    setcookie('userId', '', time() - 3600, '/'); // Expire the cookie
    setcookie('userLevel', '', time() - 3600, '/');
    
    // Redirect to the login page
    header("Location: login.php");
    exit();
}

require_once('Handlers/dbh.inc.php');

        $userId = $_COOKIE['userId'];
        
        $getUserLevelQuery = "SELECT userLevel FROM People WHERE id = :userId";

        $getUserLevelStmt = $conn->prepare($getUserLevelQuery);

        $getUserLevelStmt->bindParam(':userId', $userId);

        $getUserLevelStmt->execute();

        $userLevelResult = $getUserLevelStmt->fetch(PDO::FETCH_ASSOC);
        $userLevel = $userLevelResult['userLevel'];

// Fetch inventory from the database
$inventoryQuery = "SELECT i.id,
                        i.storeId,
                        hs.storeName,
                        i.name,
                        i.availableQuantity,
                        CAST(i.price AS DECIMAL(10, 2)) AS price,
                        i.description,
                        c.productCount AS inCart
                        FROM Inventory i
                        JOIN (
                            SELECT hs1.id, hs1.name AS storeName
                            FROM HardwareStores hs1
                        ) hs ON i.storeId = hs.id
                        LEFT JOIN (
                            SELECT *
                            FROM Carts c1
                            WHERE c1.userId = :userId
                        ) c ON i.id = c.inventoryId";

$inventoryStmt = $conn->prepare($inventoryQuery);

$inventoryStmt->bindParam(':userId', $userId);

$inventoryStmt->execute();

$inventory = $inventoryStmt->fetchAll(PDO::FETCH_ASSOC);

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
        .headerClass {
            float: left;
            margin-left: 2rem;
            margin-top: 2rem;
            margin-right: -6rem
        }
        h1 {
            text-align: left;
            margin-left: 45rem;
        }
    </style>
</head>
<body>
    <header>
        <!-- Logout form -->
        <form method="post" action="" class="headerClass">
            <button type="submit" name="logout">Logout</button>
        </form>
        <button onclick="window.location.href='Cart.php'" class="headerClass" style="margin-left: 7rem">View Cart</button>
        <button onclick="window.location.href='Invoices.php'" class="headerClass" style="margin-left: 7rem">View Invoices</button>
        <?php if ($userLevel < 2): ?>
            <button onclick="window.location.href='AddToStore.php'" class="headerClass" style="margin-left: 7rem;">Add Item to Store</button>
        <?php endif; ?>

        <h1>Hardware Store - Shop</h1>
    </header>
    <div class="container">
        <h2>Products</h2>
        <div class="products">
            <?php
            
                foreach ($inventory as $row) {
                    echo "<div class='product'>
                            <h3>Product Name: " . $row['name'] . "</h3>
                            <p>Store Name: " . $row['storeName'] . "</p>
                            <p>Description: " . $row['description'] . "</p>
                            <p>Available Quantity: " . ($row['availableQuantity'] - $row['inCart']) . "</p>
                            <p>Price: $" . $row['price'] . "</p>
                            <form action='Handlers/ShopHandler.inc.php' method='post'>
                                <label for='quantity'>Quantity:</label>
                                <input type='number' id='quantity' name='quantity' min='1' max='" . $row['availableQuantity'] . "' value='1'>
                                <input type='hidden' name='inventoryId' value='" . $row['id'] . "'>
                                <button type='submit' name='submit'>Add to Cart</button>
                            </form>
                        </div>";
                    }
            ?>
        </div>
    </div>
    <?php
    if (isset($_POST['logout'])) {
        // Remove the userId cookie
        setcookie('userId', '', time() - 3600, '/'); // Expire the cookie
        setcookie('userLevel', '', time() - 3600, '/');
        // Redirect to the login page or any other desired page
        header("Location: login.php"); // Replace 'login.php' with your desired page
        exit(); // Terminate the script
    }
    ?>
</body>
</html>
