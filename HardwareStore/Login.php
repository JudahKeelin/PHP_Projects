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
        <h1>Hardware Store - Login</h1>
    </header>
    <div class="container">
        <h2>Login</h2>
        <form action="Handlers/LoginHandler.inc.php" method="get">
            <label for="userName">UserName:</label>
            <input type="text" id="userName" name="userName" required>
            <br>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <br>
            <button type="submit" name="submit">Login</button>
        </form>
        <button onclick="popupTest()">test</button>
    </div>

    <script>
        function popupTest() {
            alert("This is a test popup.");
        }
    </script>
</body>
</html>