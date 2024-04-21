<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            require_once('dbh.inc.php');

            $userId = $_COOKIE['userId'];
            $invoiceId = uniqid();
            $defaultStatus = "Pending";

            // Get data for invoice
            $getCartQuery = "SELECT c.id, c.userId, c.inventoryId, c.productCount, i.storeId, i.availableQuantity, i.name, CAST(i.price AS DECIMAL(10, 2)) AS price
                            FROM Carts c
                            JOIN Inventory i ON c.inventoryId = i.id
                            WHERE c.userId = :userId";
            
            $getCartStmt = $conn->prepare($getCartQuery);

            $getCartStmt->bindParam(':userId', $userId);

            $getCartStmt->execute();

            $cartItems = $getCartStmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($cartItems as $cartItem) {
                // Create invoice
                $invoiceQuery = "INSERT INTO Invoices (invoiceId, storeId, userId, inventoryId, productCount, status)
                                VALUES (:invoiceId, :storeId, :userId, :inventoryId, :productCount, :status)";

                $invoiceStmt = $conn->prepare($invoiceQuery);

                $invoiceStmt->bindParam(':invoiceId', $invoiceId);
                $invoiceStmt->bindParam(':storeId', $cartItem['storeId']);
                $invoiceStmt->bindParam(':userId', $userId);
                $invoiceStmt->bindParam(':inventoryId', $cartItem['inventoryId']);
                $invoiceStmt->bindParam(':productCount', $cartItem['productCount']);
                $invoiceStmt->bindParam(':status', $defaultStatus);

                $invoiceStmt->execute();

                $invoiceStmt = null;

                // Update inventory
                $newInventoryCount = $cartItem['availableQuantity'] - $cartItem['productCount'];

                $updateInventoryQuery = "UPDATE Inventory SET availableQuantity = :newInventoryCount WHERE id = :inventoryId";

                $updateInventoryStmt = $conn->prepare($updateInventoryQuery);

                $updateInventoryStmt->bindParam(':newInventoryCount', $newInventoryCount);
                $updateInventoryStmt->bindParam(':inventoryId', $cartItem['inventoryId']);

                $updateInventoryStmt->execute();

                $updateInventoryStmt = null;
            }




            // Delete items from cart
            $deleteQuery = "DELETE FROM Carts WHERE userId = :userId";

            $deleteStmt = $conn->prepare($deleteQuery);

            $deleteStmt->bindParam(':userId', $userId);

            $deleteStmt->execute();

            $conn = null;
            $getCartStmt = null;
            $deleteStmt = null;

            header("Location: ../Cart.php");

            die();
        } catch (PDOException $e) {
            die("Query Failed: " . print_r($e));
        }


    } else {
        echo "Error: Invalid request method.";
        header("Location: ../Cart.php");
    }
