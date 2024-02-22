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

// Retrieve the parking status for the current user
$status_query = "SELECT seat_no and seat_booked FROM seats WHERE bus_no = $user_id";
$status_result = $conn->query($status_query);
checkQuery($status_result, $conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="status.css">
    <title>Bus booking system - Booking Status</title>
</head>
<body>
    <div class="container">
        <header>
            <h1>GUARDIAN ANGEL BUS BOOKING SYSTEM</h1>
        </header>

        <section>
            <h2>booking Status</h2>
            <?php
            // Display parking status
            if ($status_result->num_rows > 0) {
                echo '<p>Your current booking status:</p>';
                echo '<ul>';
                while ($row = $status_result->fetch_assoc()) {
                    $status = ($row['is_available'] == 1) ? 'Available' : 'Occupied';
                    echo '<li>booking Spot ' . $row['seat_no'] . ': ' . $status . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>You currently do not have any booked seat.</p>';
            }
            ?>
            <p><a href="dashboard.php">Back to Dashboard</a></p>
        </section>
    </div>
    <br></br>
    <br></br>
    <br></br>
    <br></br>
    <br></br>
    <br></br>
    <br></br>
    <br></br>
    <br></br>

    <footer>
        <div class="container">
            <p>&copy; GUARDIAN ANGEL BUS BOOKING. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

<?php
// Function to check the success of database queries
function checkQuery($result, $conn) {
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
}
?>
