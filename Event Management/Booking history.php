
<?php 
session_start();
include 'db.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: LOG IN.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Query to fetch user details along with the booking information
$query = "SELECT bookings.*, users.username FROM bookings 
          JOIN users ON bookings.user_id = users.id
          WHERE bookings.user_id = ? ORDER BY bookings.event_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id); // Bind the user_id as an integer
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
    <style>
        /* Page and table styling */
        body {
            background-color: white;
            font-family: Arial, sans-serif;
            color: #fff;
            margin: 3;
            padding: 0;
        }

        h1 {
            background-color: brown;
            color: yellow;
            text-align: center;
            padding: 20px 0;
            margin: 0;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #8B0000;
        }

        th {
            background-color: #8B0000;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        td {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
            border: 1px solid #8B0000;
        }

        tr:nth-child(even) td {
            background-color: #555;
        }
    </style>
</head>
<body>

    <h1>Booking History</h1>
    <table>
        <thead>
            <tr>
                <th>Username</th> <!-- Displaying Username -->
                <th>User ID</th>
                <th>No. of Guests</th>
                <th>Place</th>
                <th>Date</th>
                <th>Food Type</th>
                <th>Breakfast</th>
                <th>Lunch</th>
                <th>Snack</th>
                <th>Dinner</th>
                <th>Lights</th>
                <th>Flower</th>
                <th>Seating</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if there are any bookings
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['username'] . "</td>"; // Displaying the username
                    echo "<td>" . $row['user_id'] . "</td>"; // Displaying the user_id instead of bid
                    echo "<td>" . $row['no_of_guests'] . "</td>";
                    echo "<td>" . $row['event_place'] . "</td>";
                    echo "<td>" . $row['event_date'] . "</td>";
                    echo "<td>" . $row['food'] . "</td>";
                    echo "<td>" . (strpos($row['food'], 'Breakfast') !== false ? 'Yes' : 'No') . "</td>";
                    echo "<td>" . (strpos($row['food'], 'Lunch') !== false ? 'Yes' : 'No') . "</td>";
                    echo "<td>" . (strpos($row['food'], 'Tea and snacks') !== false ? 'Yes' : 'No') . "</td>";
                    echo "<td>" . (strpos($row['food'], 'Dinner') !== false ? 'Yes' : 'No') . "</td>";
                    echo "<td>" . $row['lighting'] . "</td>";
                    echo "<td>" . $row['flowers'] . "</td>";
                    echo "<td>" . $row['seating'] . "</td>";
                    // Assuming the total cost is calculated based on the number of guests and food options
                    $total_cost = $row['no_of_guests'] * 200; // Adjust this as per your calculation
                    echo "<td>" . $total_cost . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='15'>No bookings found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
<?php
// Close the database connection
$conn->close();
?>
