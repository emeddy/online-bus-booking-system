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
    // Process the parking request
    $seats = $_POST["seats"];

    // Basic input validation (you should enhance this)
    if (empty($seats)) {
        $error_message = "Please select a parking spot.";
    } else {
        // Check if the selected parking spot is available
        $check_spot_query = "SELECT * FROM seats WHERE seat_no = $seats AND is_available = 1";
        $check_spot_result = $conn->query($check_spot_query);
        checkQuery($check_spot_result, $conn);

        if ($check_spot_result->num_rows == 1) {
            // Update the parking spot status to occupied
            $update_spot_query = "UPDATE seats SET is_available = 0, user_id = $user_id WHERE seat_no = $seats";
            $update_spot_result = $conn->query($update_spot_query);
            checkQuery($update_spot_result, $conn);

            $success_message = "Your seat has been booked $seats.";
        } else {
            $error_message = "Selected seat spot is not available. Please choose another one.";
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
    <link rel="stylesheet" href="park.css">
    <title>CodeCraft Parking System - Park</title>
</head>
<body>
    <div class="container">
        <header>
            <h1>GUARDIAN ANGEL BUS BOOKING SYSTEM</h1>
        </header>

        <section>
            <h2>choose booking</h2>
            <?php
            // Display success or error message if there is one
            if (isset($success_message)) {
                echo '<p class="success">' . $success_message . '</p>';
            } elseif (isset($error_message)) {
                echo '<p class="error">' . $error_message . '</p>';
            }
            ?>

            <form action="park.php" method="post">
                <label for="seats">Choose your bus here:</label>
                <select id="seats" name="seats" required>
                    <div>
                    <option value="10">1: bus: KDA</option>
                    <option value="10">seat number one</option>
                    <option value="10">seat number two </option>
                    <option value="10">seat number three</option>
                    <option value="10">seat number four</option>
                    <option value="10">seat number five</option>
                    <option value="10">seat number six</option>
                    <option value="10">seat number seven</option>
                    <option value="10">2: KBA</option>
                    <option value="10">seat number one</option>
                    <option value="10">seat number two </option>
                    <option value="10">seat number three</option>
                    <option value="10">seat number four</option>
                    <option value="10">seat number five</option>
                    <option value="10">seat number six</option>
                    <option value="10">seat number seven</option>
                    <option value="10">3: KAB</option>
                    <option value="10">seat number one</option>
                    <option value="10">seat number two </option>
                    <option value="10">seat number three</option>
                    <option value="10">seat number four</option>
                    <option value="10">seat number five</option>
                    <option value="10">seat number six</option>
                    <option value="10">seat number seven</option>
                    <option value="10">4: KXA</option>
                    <option value="10">seat number one</option>
                    <option value="10">seat number two </option>
                    <option value="10">seat number three</option>
                    <option value="10">seat number four</option>
                    <option value="10">seat number five</option>
                    <option value="10">seat number six</option>
                    <option value="10">seat number seven</option>
        </div>
                     <label for="seats">Choose your seat here:</label>
                     <ul class="Showcase">
                        <li>
                            <div class="seat"></div>
                            <small>N/A</small>
                        </li>
                        <li>
                            <div class="seat selected"></div>
                            <small>Selected</small>
                        </li>
                        <li>
                            <div class="seat ocuppied"></div>
                            <small>Ocuppied</small>
                        </li>
                     </ul>
                     <div class="container">
                        <div class="screen"></div>

                        <div class="row">
                            <div class="seat"></div>
                            <div class="seat"></div>
                            <div class="seat occupied"></div>
                            <div class="seat occupied"></div>
                            <div class="seat"></div>
                            <div class="seat occupied"></div>
                            <div class="seat"></div>
                            <div class="seat"></div>
                     </div>
                     <div class="row">
                            <div class="seat"></div>
                            <div class="seat"></div>
                            <div class="seat"></div>
                            <div class="seat occupied"></div>
                            <div class="seat"></div>
                            <div class="seat occupied"></div>
                            <div class="seat occupied"></div>
                            <div class="seat"></div>
                     </div>
                     <div class="row">
                            <div class="seat"></div>
                            <div class="seat"></div>
                            <div class="seat"></div>
                            <div class="seat occupied"></div>
                            <div class="seat occupied"></div>
                            <div class="seat occupied"></div>
                            <div class="seat"></div>
                            <div class="seat"></div>
                            <input type="submit">
                     </div>
                     <p class="text">
                        you have selected <span id="count">0</span> seats for a price of Ksh<span id="total">0</span>
                        

                    <?php
                    // Retrieve available parking spots from the database
                    $available_seats_query = "SELECT seat_no FROM seats WHERE is_available = 1";
                    $available_seats_result = $conn->query($available_seats_query);
                    checkQuery($available_seats_result, $conn);

                    while ($spot = $available_seats_result->fetch_assoc()) {
                        echo '<option value="' . $spot['seat_no'] . '">' . $spot['seat_no'] . '</option>';
                    }
                    ?>
                </select>

                <button type="submit">choose booking</button>
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
