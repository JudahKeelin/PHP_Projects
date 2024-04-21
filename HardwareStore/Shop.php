<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hardware Store - Shop</title>
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
        .container {
            max-width: 960px;
            margin: 20px auto;
            padding: 0 20px;
        }
        /* Add more styles as needed */
    </style>
</head>
<body>
    <header>
        <h1>Hardware Store - Shop</h1>
    </header>
    <div class="container">
        <h2>Products</h2>
        <div class="products">
            <?php
            try {
                require_once('Handlers/dbh.inc.php');

                $sql = "SELECT p.*, iv.productCount FROM Products p
                JOIN Inventory iv ON p.id = iv.productId";

                $result = $conn->query($sql);

                if ($result) {
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div class='product'>";
                        echo "<h3>Product Name: " . $row['name'] . "</h3>";
                        echo "<p>Description: " . $row['description'] . "</p>";
                        echo "<p>Price: $" . $row['price'] . "</p>";
                        echo "<label for='quantity_" . $row['id'] . "'>Quantity:</label>";
                        echo "<input type='number' id='quantity_" . $row['id'] . "' name='quantity_" . $row['id'] . "' min='1' value='1'>";
                        echo "<button onclick='addToCart(" . $row['id'] . ", " . $row['productCount'] . ")'>Add to Cart</button>";
                        echo "</div>";
                    }
                } else {
                    echo "No products found.";
                }
            } catch (PDOException $e) {
                echo "Error connecting to SQL Server.<br>";
                echo "Spot 2<br>";
                echo "Error message: " . $e->getMessage(); // Print the error message
            }
            ?>
            <a href="Cart.php" class="cart-button">View Cart</a>
        </div>
    </div>
    <script>
        function addToCart(productId, productCount) {
            <?php
                echo "console.log('Product ID: ' + productId);";
                echo "console.log('Product Count: ' + productCount);";
            ?>
                var quantity = document.getElementById("quantity_" + productId).value;
            
            // Check if requested quantity is higher than available supply
            if (parseInt(quantity) > parseInt(productCount)) {
                alert("Requested quantity exceeds available supply.");
                return;
            }
            
            // Send productId and quantity to Cart.php using AJAX
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    alert("Added " + quantity + " units of product " + productId + " to the cart.");
                    location.reload(); // Refresh the page to update product counts
                }
            };
            xhr.open("POST", "Cart.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("userId=" + 4 + "&productId=" + productId + "&quantity=" + quantity);
        }
    </script>
</body>
</html>

