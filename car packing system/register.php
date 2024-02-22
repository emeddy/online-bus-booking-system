<?php
require_once('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Basic input validation (you should enhance this)
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error_message = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Password and confirm password do not match.";
    } else {
        // Check if the username is already taken
        $check_username_query = "SELECT * FROM users WHERE username = '$username'";
        $check_username_result = $conn->query($check_username_query);
        checkQuery($check_username_result, $conn);

        if ($check_username_result->num_rows > 0) {
            $error_message = "Username is already taken. Please choose a different one.";
        } else {
            // Hash the password (you should use password_hash() in a production environment)
            $hashed_password = md5($password);

            // Insert user into the database
            $insert_user_query = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
            $insert_user_result = $conn->query($insert_user_query);
            checkQuery($insert_user_result, $conn);

            // Redirect to login page after successful registration
            header("Location: login.php");
            exit();
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
    <link rel="stylesheet" href="register.css">
    <title>GUARDIAN ANGEL BUS BOOKING SYSTEM - Register</title>
</head>
<body>
    <div class="container">
        <header>
            <h1>GUARDIAN ANGEL BUS BOOKING SYSTEM</h1>
        </header>

        <section>
            <h2>Register</h2> 
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

                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <button type="submit">Register</button>
            </form>

            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </section>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2023 GUARDIAN ANGEL BUS BOOKING SYSTEM. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
