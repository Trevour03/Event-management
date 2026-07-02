<?php
session_start();
include 'db.php'; // Include the database connection

$booking_status = '';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: LOG IN.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form inputs
    $user_id = $_POST['user_id'];
    $event_type = $_POST['event_type'];
    $event_date = $_POST['date'];

    // Prepare the SQL query to fetch the booking status
    $query = "SELECT status FROM bookings WHERE user_id = ? AND event_type = ? AND event_date = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $user_id, $event_type, $event_date);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any booking exists for the given criteria
    if ($result->num_rows > 0) {
        $booking = $result->fetch_assoc();
        $booking_status = "Your booking status is: " . $booking['status'];
    } else {
        $booking_status = "No booking found for the specified details.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Status</title>
    <style>
        body {
            background-image: url('image/bkg status.png');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
        }
        .container {
            width: 350px;
            margin: 100px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="date"], select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            border-radius: 4px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .status {
            margin-top: 20px;
            font-weight: bold;
            color: #333;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Check Booking Status</h2>
    <form method="POST">
        <label for="user_id">Enter User ID:</label>
        <input type="text" id="user_id" name="user_id" required>

        <label for="event_type">Enter Event Type:</label>
        <select id="event_type" name="event_type" required>
            <option value="Marriage">Marriage</option>
            <option value="Graduation">Graduation</option>
            <option value="Family Function">Family Function</option>
            <option value="Birthday Party">Birthday Party</option>
            <option value="Anniversary Party">Anniversary Party</option>
            <option value="Farewell Party">Farewell Party</option>
            <option value="College Event">College Event</option>
            <option value="Crusade Event">Crusade Event</option>
        </select>

        <label for="event_date">Enter Event Date:</label>
        <input type="date" id="event_date" name="date" required>

        <input type="submit" value="OK">
    </form>

    <?php if ($booking_status): ?>
        <div class="status"><?php echo $booking_status; ?></div>
    <?php endif; ?>
</div>

</body>
</html>
