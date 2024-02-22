<?php
session_start();
require_once('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Basic input validation (you should enhance this)
    if (empty($username) || empty($password)) {
        $error_message = "Username and password are required.";
    } else {
        // Check if the username and password match a user in the database
        $check_user_query = "SELECT * FROM users WHERE username = '$username'";
        $check_user_result = $conn->query($check_user_query);
        checkQuery($check_user_result, $conn);

        if ($check_user_result->num_rows == 1) {
            $user = $check_user_result->fetch_assoc();
            // Verify the password (you should use password_verify() in a production environment)
            if (md5($password) == $user['password']) {
                // Password is correct, set session variables and redirect to a secure page
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: dashboard.php"); // Redirect to a secure page
                exit(); // Ensure script stops executing after redirect
            } else {
                $error_message = "Incorrect password.";
            }
        } else {
            $error_message = "User not found. Please check your username.";
        }
    }
}

// Function to check the success of database queries
function checkQuery($result, $conn) {
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>GUARDIAN ANGEL BUS BOOKING SYSTEM - Login</title>
</head>
<body>
    <div class="container">
        <header>
            <h1>GUARDIAN ANGEL BUS BOOKING SYSTEM</h1>
        </header>

        <section>
            <h2>Login</h2>
            <?php
            // Display error message if there is one
            if (isset($error_message)) {
                echo '<p class="error">' . $error_message . '</p>';
            }
            ?>

            <form action="" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Login</button>
            </form>

            <p>Don't have an account? <a href="register.php">Register here</a>.</p>
        </section>
    </div>
    <br></br>
    <br></br>
    <br></br>
    <br></br>
    <br></br>

    <footer>
        <div class="container">
        <main> <div><p>Experience travel in style</p>
        </main>
            <p>&copy; 2023 GUARDIAN ANGEL BUS BOOKING SYSTEM. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
