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
</head>
<body>
<h1>Add Item to Store</h1>
<form action="Handlers/AddToStoreHandler.inc.php" method="post">
    <label for="name">Item Name:</label>
    <input type="text" id="name" name="name" required><br><br>
    <label for="description" style="display: block;">Description:</label>
    <textarea id="description" name="description" rows="3" cols="25" style="display: block;" required></textarea><br><br>
    <label for="price">Price:</label>
    <input type="number" id="price" name="price" step="0.01" required><br><br>
    <label for="availableQuantity">Quantity:</label>
    <input type="number" id="availableQuantity" name="availableQuantity" required><br><br>
    <button type="submit" name="submit">Add Item</button>
</form>
<button onclick="window.location.href='shop.php'">Back to Shop</button>
</body>
</html>
