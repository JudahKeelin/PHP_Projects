<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userName = $_POST['userName'];
    $userLevel = 2;
    $address = ($_POST['address'] . " " . $_POST['city'] . " " . $_POST['state'] . " " . $_POST['zip']);
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        require_once('dbh.inc.php');

        $query = "INSERT INTO People ([userName], [userLevel], [address], [email], [phone], [password])
                  VALUES (:userName, :userLevel, :address, :email, :phone, :password)";

        $stmt = $conn->prepare($query);

        $stmt->bindParam(':userName', $userName);
        $stmt->bindParam(':userLevel', $userLevel);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':password', $password);

        $stmt->execute();

        $conn = null;
        $stmt = null;

        header("Location: ../Login.php");

        die();
    } catch (PDOException $e) {
        die("Query Failed: " . print_r($e));
    }

} else {
    echo "Error: Invalid request method.";
    header("Location: ../Signup.php");
}
