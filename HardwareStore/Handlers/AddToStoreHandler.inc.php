<?php
require_once('dbh.inc.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $storeId = 1;
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['availableQuantity'];
    $description = $_POST['description'];
    
    try {
        // Prepare and execute query
        
        $query = "INSERT INTO HardwareStores (storeId, name, price, availableQuantity, description ) 
        VALUES (:storeId ,:name, :price, :availableQuantity, :description)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':storeId', $storeId);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':availableQuantity', $quantity);
        $stmt->bindParam(':description', $description);
        $stmt->execute();

        
        header("Location: ../AddToStore.php");
        exit();
    } catch (PDOException $e) {
        // Handle database errors
        die("Query Failed: " . $e->getMessage());
    }
}