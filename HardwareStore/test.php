<?php


try {
    require_once('dbh.inc.php');

    $sql = "SELECT * FROM Carts";

    $result = $conn->query($sql);

    while ($row = $result -> fetch(PDO::FETCH_ASSOC)){
        echo "ID : ".$row['id']."<br>";
        echo "Username : ".$row['userId']."<br>";
        echo "User Level : ".$row['productId']."<br>";
        echo "Address : ".$row['ProductCount']."<br>";
       
        
        echo "<br>";
    }
} catch (PDOException $e) {
    print("Error connecting to SQL Server.");
    print("Spot 2");
    die(print_r($e));
}


?>