<?php
// Handle form submission
require_once('dbh.inc.php');
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['productId']) && isset($_POST['quantity'])) {
    $productId = $_POST['productId'];
    $quantity = $_POST['quantity'];
    $userId = $_COOKIE['userId']
    

    try {
        // Check if the product exists in the inventory and has sufficient stock
        $inventoryQuery = "SELECT productCount FROM Inventory WHERE productId = ?";
        $inventoryStmt = $conn->prepare($inventoryQuery);
        $inventoryStmt->execute([$productId]);
        $inventoryResult = $inventoryStmt->fetch(PDO::FETCH_ASSOC);

        if ($inventoryResult && $inventoryResult['productCount'] >= $quantity) {
            // Insert into the Cart table
            
            $insertCartQuery = "INSERT INTO Carts (userId, productId, productCount)
            VALUES (:userId, :productId, :productCount)";
            $insertCartStmt = $conn->prepare($insertCartQuery);
            $insertCartStmt->execute([$_COOKIE['userId'], $productId, $quantity]);

            // Update the product count in the Inventory table
            if($userId == $inventoryResult['userId'] && $inventoryResult['productId'] == $productId){
                $newCount = $inventoryResult['productCount'] - $quantity;
                $updateInventoryQuery = "UPDATE Inventory SET productCount = ? WHERE productId = ?";
                $updateInventoryStmt = $conn->prepare($updateInventoryQuery);
                $updateInventoryStmt->execute([$newCount, $productId]);
            }
           

            // Redirect back to shop page after successful addition to cart
            header("Location: ../Shop.php");
            exit();
        } else {
            // Redirect back to shop page with error message if insufficient stock
            header("Location: ../Shop.php?error=insufficient_stock");
            exit();
        }
    } catch (PDOException $e) {
        // Handle database errors
        die("Query Failed: " . $e->getMessage());
    }
}
?>