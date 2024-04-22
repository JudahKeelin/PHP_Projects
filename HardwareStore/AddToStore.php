<?php

// Check if a user is logged in
if (!isset($_COOKIE['userId'])) {
    // Redirect to login page or handle authentication
    header("Location: login.php");
    exit();
}

// Include your database connection file here
require_once('Handlers/dbh.inc.php');

if (isset($_COOKIE['userLevel']) && $_COOKIE['userLevel'] < 2) {
    // Redirect or handle unauthorized access
   header("Location: unauthorized.php");
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item to Store</title>
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
        form {
            display: inline-block;
        }
    </style>
</head>
<body>
    <header>
            <!-- Logout form -->
            <form method="post" action="" class="headerClass">
                <button type="submit" name="logout">Logout</button>
            </form>
            <button onclick="window.location.href='Shop.php'" class="headerClass" style="margin-left: 7rem">Back to Shop</button>
            <h1>Add Item to Store</h1>
    </header>
    <div class="container">
        <h2>Add Item</h2>
        <form action="Handlers/AddToStoreHandler.inc.php" method="post">
            <label for="name">Item Name:</label>
            <input type="text" id="name" name="name" required><br><br>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea><br><br>
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" required><br><br>
            <label for="availableQuantity">Quantity:</label>
            <input type="number" id="availableQuantity" name="availableQuantity" required><br><br>
            <button type="submit" name="submit">Add Item</button>
        </form>
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
