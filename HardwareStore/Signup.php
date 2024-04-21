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
        <h1>Hardware Store - Signup</h1>
    </header>
    <div class="container">
        <h2>Signup</h2>
        <form action="Handlers/SignupHandler.inc.php" method="post">
            <label for="userName">UserName:</label>
            <input type="text" id="userName" name="userName" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required>
            <br>
            <br>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
            <label for="city">City:</label>
            <input type="text" id="city" name="city" required>
            <label for="state">State:</label>
            <input type="text" id="state" name="state" required>
            <label for="zip">Zip:</label>
            <input type="text" id="zip" name="zip" required>
            <br>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <br>
            <button type="submit" name="submit">Signup</button>
        </form>

    </div>
</body>
</html>