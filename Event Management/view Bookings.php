<?php
// Include the database connection file
include('db.php');

// Handle approve or cancel request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['action'])) {
    $user_id = intval($_POST['user_id']);
    $action = $_POST['action'];

    // Validate the action to prevent SQL injection
    if ($action === 'approve' || $action === 'cancel') {
        // Set status based on the action
        $status = ($action === 'approve') ? 'Approved' : 'Cancelled';

        // Prepare the SQL query to update the status in the bookings table
        $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE user_id = ?");
        $stmt->bind_param('si', $status, $user_id);

        if ($stmt->execute()) {
            echo "Booking has been " . $status . " successfully.";
        } else {
            echo "Error updating booking status.";
        }

        $stmt->close();
    } else {
        echo "Invalid action.";
    }
}

// Fetch all bookings for displaying in the table
$sql = "SELECT b.*, u.username 
        FROM bookings b 
        LEFT JOIN users u 
        ON b.user_id = u.id"; // Adjust 'id' if necessary

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bookings</title>
    <style>

    </style>       

</head>
<body>
    <h1>View Bookings</h1>
    <table>
        <thead>
            <tr>
                <th>Username</th>
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
                echo "<td>" . htmlspecialchars($row['username'] ?? 'N/A') . "</td>"; 
                echo "<td>" . htmlspecialchars($row['user_id'] ?? 'N/A') . "</td>";
                echo "<td class='center'>" . htmlspecialchars($row['no_of_guests'] ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($row['event_place'] ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($row['event_date'] ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($row['food'] ?? 'N/A') . "</td>";
                echo "<td class='center'>" . (strpos($row['food'], 'Breakfast') !== false ? 'Yes' : 'No') . "</td>";
                echo "<td class='center'>" . (strpos($row['food'], 'Lunch') !== false ? 'Yes' : 'No') . "</td>";
                echo "<td class='center'>" . (strpos($row['food'], 'Tea and snacks') !== false ? 'Yes' : 'No') . "</td>";
                echo "<td class='center'>" . (strpos($row['food'], 'Dinner') !== false ? 'Yes' : 'No') . "</td>";
                echo "<td>" . htmlspecialchars($row['lighting'] ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($row['flowers'] ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($row['seating'] ?? 'N/A') . "</td>";

                // Assuming the total cost is calculated based on the number of guests
                $total_cost = $row['no_of_guests'] * 200;
                echo "<td class='center'>" . htmlspecialchars($total_cost) . "</td>";
                echo "<td>" . htmlspecialchars($row['status'] ?? 'N/A') . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='15' class='no-data'>No bookings found.</td></tr>";
        }

        // Close the database connection
        $conn->close();
        ?>
        </tbody>
    </table>

    <!-- Section for admin to approve or cancel bookings -->
    <div class="form-container">
        <label for="user_id">Enter User ID to Approve/Cancel Booking:</label>
        <input type="text" id="user_id" name="user_id" placeholder="User ID">
        <button class="approve-btn" onclick="updateBooking('approve')">Approve</button>
        <button class="cancel-btn" onclick="updateBooking('cancel')">Cancel</button>
    </div>

    <script>
        function updateBooking(action) {
            var userId = document.getElementById('user_id').value;
            if (userId) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '', true); // Post back to the same page
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        alert(xhr.responseText); // Display success or error message
                        location.reload(); // Reload the page to reflect changes
                    }
                };
                xhr.send('user_id=' + userId + '&action=' + action);
            } else {
                alert('Please enter a User ID.');
            }
        }
    </script>
</body>
</html>
