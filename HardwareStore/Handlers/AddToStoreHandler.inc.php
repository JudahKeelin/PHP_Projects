<?php
require_once('dbh.inc.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['availableQuantity'];
    $description = $_POST['description'];
    
    try {
        // Prepare and execute query
        $getStoreQuery = "SELECT hs.id,
                                hs.name,
                                hs.managerId
                        FROM HardwareStores hs
                        WHERE hs.managerId = :userId";
        
        $getStoreStmt = $conn->prepare($getStoreQuery);
        $getStoreStmt->bindParam(':userId', $_COOKIE['userId']);
        $getStoreStmt->execute();
        $storeIdResult = $getStoreStmt->fetch(PDO::FETCH_ASSOC);
        $storeId = $storeIdResult['id'];
        if (!$storeId) {
            $storeId = 1;
        }
        
        $query = "INSERT INTO Inventory ( storeId, name, price, availableQuantity, description ) 
        VALUES ( :storeId ,:name, :price, :availableQuantity, :description )";
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