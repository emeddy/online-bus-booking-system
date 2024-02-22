<?php
session_start();
require_once('db_connection.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve user information
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the exit request
    $seats = $_POST["seats"];

    // Basic input validation (you should enhance this)
    if (empty($seats)) {
        $error_message = "Please select the parking spot you want to exit.";
    } else {
        // Check if the selected parking spot is occupied by the current user
        $check_spot_query = "SELECT * FROM seats WHERE seat_no = $seats AND user_id = $user_id AND is_available = 0";
        $check_spot_result = $conn->query($check_spot_query);
        checkQuery($check_spot_result, $conn);

        if ($check_spot_result->num_rows == 1) {
            // Update the parking spot status to available
            $update_spot_query = "UPDATE seats SET is_available = 1, user_id = NULL WHERE seat_no = $seats";
            $update_spot_result = $conn->query($update_spot_query);
            checkQuery($update_spot_result, $conn);

            $success_message = "You have successfully exited parking spot $seats.";
        } else {
            $error_message = "Invalid parking spot selected. Please choose the correct one.";
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
    <link rel="stylesheet" href="exit.css">
    <title>GUARDIAN ANGEL BUS BOOKING SYSTEM - Exit</title>
</head>
<body>
    <div class="container">
        <header>
            <h1>GUARDIAN ANGEL BUS BOOKING SYSTEM</h1>
        </header>

        <section>
            <h2>Cancel booking</h2>
            <?php
            // Display success or error message if there is one
            if (isset($success_message)) {
                echo '<p class="success">' . $success_message . '</p>';
            } elseif (isset($error_message)) {
                echo '<p class="error">' . $error_message . '</p>';
            }
            ?>

            <form action="exit.php" method="post">
                <label for="seats">Select seats:</label>
                <select id="seats" name="seats" required>
                    <?php
                    // Retrieve occupied parking spots by the current user from the database
                    $occupied_spots_query = "SELECT seat_no FROM seats WHERE user_id = $user_id AND is_available = 0";
                    $occupied_spots_result = $conn->query($occupied_spots_query);
                    checkQuery($occupied_spots_result, $conn);

                    while ($spot = $occupied_spots_result->fetch_assoc()) {
                        echo '<option value="' . $spot['seat_no'] . '">' . $spot['seat_no'] . '</option>';
                    }
                    ?>
                </select>

                <button type="submit">Cancel booking</button>
            </form>

            <p><a href="dashboard.php">Back to Dashboard</a></p>
        </section>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2023 GUARDIAN ANGEL BUS BOOKING SYSTEM. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
