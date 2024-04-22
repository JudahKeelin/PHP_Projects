<?php
    // Check if a user is logged in
    if (!isset($_COOKIE['userId'])) {
        // Redirect to login page or handle authentication
        header("Location: login.php");
        exit();
    }
    try {
        // Include your database connection file here
        require_once('Handlers/dbh.inc.php');

        $userId = $_COOKIE['userId'];
        
        $getUserLevelQuery = "SELECT userLevel FROM People WHERE id = :userId";

        $getUserLevelStmt = $conn->prepare($getUserLevelQuery);

        $getUserLevelStmt->bindParam(':userId', $userId);

        $getUserLevelStmt->execute();

        $userLevelResult = $getUserLevelStmt->fetch(PDO::FETCH_ASSOC);
        $userLevel = $userLevelResult['userLevel'];

        // Fetch invoices from the database
        $getInvoicesQuery = "SELECT ivo.id,
                                    ivo.timestamp,
                                    ivo.invoiceId,
                                    ivo.userId,
                                    ivo.status,
                                    ivo.storeId,
                                    hs.name AS storeName,
                                    ivo.inventoryId,
                                    nve.name AS productName,
                                    ivo.productCount,
                                    CAST(nve.price AS DECIMAL(10, 2)) AS price,
                                    hs.managerId
                                FROM Invoices ivo
                                JOIN (
                                SELECT nve1.id, nve1.name, nve1.price
                                FROM Inventory nve1
                                ) nve ON ivo.inventoryId = nve.id
                                JOIN (
                                SELECT hs1.id, hs1.name, hs1.managerId
                                FROM HardwareStores hs1
                                ) hs ON hs.id = ivo.storeId
                                WHERE (
                                    :userLevela = 0
                                ) OR (
                                    :userLevelb = 1 AND :userIda = hs.managerId
                                ) OR (
                                    :userLevelc = 2 AND :userIdb = ivo.userId
                                )";

        $getInvoicesStmt = $conn->prepare($getInvoicesQuery);

        $getInvoicesStmt->bindParam(':userIda', $userId);
        $getInvoicesStmt->bindParam(':userIdb', $userId);
        $getInvoicesStmt->bindParam(':userLevela', $userLevel);
        $getInvoicesStmt->bindParam(':userLevelb', $userLevel);
        $getInvoicesStmt->bindParam(':userLevelc', $userLevel);

        $getInvoicesStmt->execute();

        $invoices = $getInvoicesStmt->fetchAll(PDO::FETCH_ASSOC);

        $tables = array();
        for ($i = 0; $i < count($invoices); $i++) {
            $invoiceId = $invoices[$i]['invoiceId'];
            if (!array_key_exists($invoiceId, $tables)) {
                $tables[$invoiceId] = array();
            }
            array_push($tables[$invoiceId], $invoices[$i]);
            
        }
    } catch (PDOException $e) {
        echo ('PDO Exception: ' . $e->getMessage() . ' (Code: ' . $e->getCode() . ')');
        die();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hardware Store - Invoices</title>
    <!-- You can include CSS stylesheets here if needed -->
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
        .headerClass {
            float: left;
            margin-left: 2rem;
            margin-top: 2rem;
            margin-right: -6rem
        }
        .container {
            max-width: 960px;
            margin: 20px auto;
            padding: 0 20px;
        }
        tbody tr:nth-child(even){
            background-color: #f2f2f2;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #333;
            color: white;
            text-align: left;
        }
        tb {
            align-items: left;
        }
        h1 {
            text-align: left;
            margin-left: 45rem;
        }
        /* Add more styles as needed */
    </style>
</head>
<body>
    <header>
        <!-- Logout form -->
        <form method="post" action="" class="headerClass">
            <button type="submit" name="logout">Logout</button>
        </form>
        <button onclick="window.location.href='shop.php'" class="headerClass" style="margin-left: 7rem">Back to Shop</button>
        <h1>Hardware Store - Invoices</h1>
    </header>
    <div class="container">
        <h2>Invoices</h2>
        <div class="invoices">
            <?php foreach ($tables as $table): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Invoice ID</th>
                            <th>userId</th>
                            <th>Store Name</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($table as $invoice): ?>
                            <tr>
                                <td><?php echo $invoice['invoiceId']; ?></td>
                                <td><?php echo $invoice['userId']; ?></td>
                                <td><?php echo $invoice['storeName']; ?></td>
                                <td><?php echo $invoice['productName']; ?></td>
                                <td>$<?php echo $invoice['price']; ?></td>
                                <td><?php echo $invoice['productCount']; ?></td>
                                <td>$<?php echo $invoice['price'] * $invoice['productCount']; ?></td>
                                <td><?php echo $invoice['status']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        </div>
        
    </div>
    <?php
    if (isset($_POST['logout'])) {
        // Remove the userId cookie
        setcookie('userId', '', time() - 3600, '/'); // Expire the cookie
        // Redirect to the login page or any other desired page
        header("Location: login.php"); // Replace 'login.php' with your desired page
        exit(); // Terminate the script
    }
    ?>
</body>
</html>