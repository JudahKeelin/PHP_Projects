<?php

require_once('dbh.inc.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $userName = $_POST['userName'];
    
    $password = $_POST['password'];

    try {
        require_once('dbh.inc.php');

        $query = "SELECT * FROM People WHERE userName = :userName";

        $stmt = $conn->prepare($query);

        $stmt->bindParam(':userName', $userName);

        $user = $stmt->execute();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user'] = $user;
            } else {
                echo '<script>
                    alert("Invalid username or password.");
                </script>';
                header("Location: ../Login.php");
                echo '<script>
                    alert("Invalid username or password.");
                </script>';
                die();
            }
        } else {
            echo '<script>
                    alert("Invalid username or password.");
                </script>';
            header("Location: ../Login.php");
            echo '<script>
                    alert("Invalid username or password.");
                </script>';
            die();
        }

        $conn = null;
        $stmt = null;

        header("Location: ../Shop.php");

        die();
    } catch (PDOException $e) {
        die("Query Failed: " . print_r($e));
    }

} else {
    echo "Error: Invalid request method.";
    header("Location: ../Login.php");
}
