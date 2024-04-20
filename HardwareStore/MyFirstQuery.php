<?php


try {
    require_once('dbh.inc.php');

    $sql = "SELECT * FROM People";

    $result = $conn->query($sql);

    while ($row = $result -> fetch(PDO::FETCH_ASSOC)){
        echo "ID : ".$row['id']."<br>";
        echo "Username : ".$row['userName']."<br>";
        echo "User Level : ".$row['userLevel']."<br>";
        echo "Address : ".$row['address']."<br>";
        echo "Email : ".$row['email']."<br>";
        echo "Phone Number : ".$row['phone']."<br>";
        
        echo "<br>";
    }
} catch (PDOException $e) {
    print("Error connecting to SQL Server.");
    print("Spot 2");
    die(print_r($e));
}


?>