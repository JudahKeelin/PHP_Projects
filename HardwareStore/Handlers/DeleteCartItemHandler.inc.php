<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $cartItemId = $_POST['cartItemId'];

        try {
            require_once('dbh.inc.php');

            $query = "DELETE FROM Carts WHERE id = :cartItemId";

            $stmt = $conn->prepare($query);

            $stmt->bindParam(':cartItemId', $cartItemId);

            $stmt->execute();

            $conn = null;
            $stmt = null;

            header("Location: ../Cart.php");

            die();
        } catch (PDOException $e) {
            die("Query Failed: " . print_r($e));
        }
    } else {
        echo "Error: Invalid request method.";
        header("Location: ../Cart.php");
    }