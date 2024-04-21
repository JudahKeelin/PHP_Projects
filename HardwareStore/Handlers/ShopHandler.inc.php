<?php
require_once('dbh.inc.php');
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['inventoryId']) && isset($_POST['quantity'])) {
    $inventoryId = $_POST['inventoryId'];
    $quantity = $_POST['quantity'];
    $userId = $_COOKIE['userId'];
    

    try {
        $cartQuery = "SELECT * FROM Carts WHERE inventoryId = :inventoryId AND userId = :userId";
        $cartStmt = $conn->prepare($cartQuery);
        $cartStmt->bindParam(':userId', $userId);
        $cartStmt->bindParam(':inventoryId', $inventoryId);
        $cartStmt->execute();
        $cartResult = $cartStmt->fetch(PDO::FETCH_ASSOC);

        $inventoryQuery = "SELECT * FROM Inventory WHERE id = :inventoryId";
        $inventoryStmt = $conn->prepare($inventoryQuery);
        $inventoryStmt->bindParam(':inventoryId', $inventoryId);
        $inventoryStmt->execute();
        $inventoryResult = $inventoryStmt->fetch(PDO::FETCH_ASSOC);

        if ($cartResult) {
            if ($cartResult['productCount'] + $quantity < $inventoryResult['productCount']) {
                $newCount = $cartResult['productCount'] + $quantity;
            } else {
                $newCount = $inventoryResult['productCount'];
            }
            
            $updateCartQuery = "UPDATE Carts SET productCount = :newCount WHERE id = :id";

            $updateCartStmt = $conn->prepare($updateCartQuery);

            $updateCartStmt->bindParam(':newCount', $newCount);
            $updateCartStmt->bindParam(':id', $cartResult['id']);

            $updateCartStmt->execute();

            $conn = null;
            $cartStmt = null;
            $inventoryStmt = null;
            $updateCartStmt = null;
           
            header("Location: ../Shop.php");
            die();
        } else {
            $insertCartQuery = "INSERT INTO Carts (userId, inventoryId, productCount)
            VALUES (:userId, :inventoryId, :productCount)";
            $insertCartStmt = $conn->prepare($insertCartQuery);
            $insertCartStmt->execute([$_COOKIE['userId'], $inventoryId, $quantity]);

            $conn = null;
            $cartStmt = null;
            $inventoryStmt = null;

            header("Location: ../Shop.php");
            die();
        }
    } catch (PDOException $e) {
        die("Query Failed: " . $e->getMessage());
    }
}
?>