<?php

require_once('dbh.inc.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userName = $_POST['userName'];
    echo "Username: " . $userName;
    $password = $_POST['password'];
    echo "Password: " . $password;

    try {
        require_once('dbh.inc.php');

        $query = "SELECT * FROM People WHERE userName = " . "'" . $userName . "'";

        $result = $conn->query($query);

        $user = $result->fetch(PDO::FETCH_ASSOC);

        if ($user != 1) {
            if (password_verify($password, $user['password'])) {
                setcookie("userId", $user['id'], time() + 86400, "/");
            } else {
                setcookie("userId", -1, time() + 86400, "/");
                header("Location: ../Login.php");
                die();
            }
        } else {
            setcookie("userId", -1, time() + 86400, "/");
            header("Location: ../Login.php");
            die();
        }

        $conn = null;
        $stmt = null;

        header("Location: ../Shop.php");

    } catch (PDOException $e) {
        die("Query Failed: " . print_r($e));
    }

} else {
    echo "Error: Invalid request method.";
    header("Location: ../Login.php");
}
