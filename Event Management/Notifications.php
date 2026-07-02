<?php
include('db.php'); // Include your database connection file

session_start();

// Check if the admin has viewed the notifications
if (!isset($_SESSION['has_viewed_notifications'])) {
    $_SESSION['has_viewed_notifications'] = false; // Set the default to false (not viewed)
}

// Function to fetch the most recent user from the users table
function fetch_new_user($conn) {
    $sql = "SELECT * FROM users ORDER BY id DESC LIMIT 1"; // Fetch the most recent user
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}

// Function to fetch the most recent booking from the bookings table
function fetch_new_booking($conn) {
    $sql = "SELECT b.*, u.username FROM bookings b JOIN users u ON b.user_id = u.id ORDER BY b.event_date DESC LIMIT 1"; // Fetch the most recent booking
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}

// Fetch all users and their details
function fetch_all_users($conn) {
    $sql = "SELECT * FROM users ORDER BY id DESC";
    $result = $conn->query($sql);

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    return $users;
}

// Fetch all bookings made by users
function fetch_all_bookings($conn) {
    // Ensure the 'status' column is part of the query
    $sql = "SELECT b.*, u.username FROM bookings b JOIN users u ON b.user_id = u.id ORDER BY b.event_date DESC";
    $result = $conn->query($sql);

    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }

    return $bookings;
}

// Get the latest user and booking
$newUser = fetch_new_user($conn);
$newBooking = fetch_new_booking($conn);

// Set session messages for new user and booking
if ($newUser) {
    $_SESSION['new_user'] = "New user {$newUser['username']} with email {$newUser['email']} has joined.";
}

if ($newBooking) {
    $_SESSION['new_booking'] = "New booking made by {$newBooking['username']} for the event: {$newBooking['event_type']} at {$newBooking['event_place']} on {$newBooking['event_date']}.";
}

// When the admin clicks to view notifications, mark the flag as true
if (isset($_GET['view_notifications'])) {
    $_SESSION['has_viewed_notifications'] = true;
    header("Location: " . $_SERVER['PHP_SELF']); // Reload the page after viewing
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications and Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .notification {
            background-color: #28a745;
            color: white;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .notification.booking {
            background-color: #007bff;
        }
        .user-list, .booking-list {
            margin-top: 20px;
        }
        .user-list table, .booking-list table {
            width: 100%;
            border-collapse: collapse;
        }
        .user-list th, .booking-list th, .user-list td, .booking-list td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .reminder {
            background-color: #ffcc00;
            color: #333;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h1>System Notifications</h1>

    <!-- Display a reminder if the admin hasn't viewed the notifications -->
    <?php if (!$_SESSION['has_viewed_notifications']): ?>
        <div class="reminder">
            You have new notifications! Please <a href="?view_notifications=true" style="color: blue;">click here</a> to view them.
        </div>
    <?php endif; ?>

    <!-- Display new user notification -->
    <?php if (isset($_SESSION['new_user'])): ?>
        <div class="notification">
            <?php echo $_SESSION['new_user']; ?>
        </div>
        <?php unset($_SESSION['new_user']); ?>
    <?php endif; ?>

    <!-- Display new booking notification -->
    <?php if (isset($_SESSION['new_booking'])): ?>
        <div class="notification booking">
            <?php echo $_SESSION['new_booking']; ?>
        </div>
        <?php unset($_SESSION['new_booking']); ?>
    <?php endif; ?>

    <!-- List all users -->
    <div class="user-list">
        <h2>All Users</h2>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>User ID</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $users = fetch_all_users($conn);
                foreach ($users as $user) {
                    echo "<tr>";
                    echo "<td>{$user['username']}</td>";
                    echo "<td>{$user['email']}</td>";
                    echo "<td>{$user['id']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- List all bookings -->
    <div class="booking-list">
        <h2>All Bookings</h2>
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>User Name</th>
                    <th>Event Type</th>
                    <th>Event Place</th>
                    <th>Event Date</th>
                    <th>Status</th> <!-- Added Status column -->
                </tr>
            </thead>
            <tbody>
                <?php
                $bookings = fetch_all_bookings($conn);
                foreach ($bookings as $booking) {
                    echo "<tr>";
                    echo "<td>{$booking['id']}</td>";
                    echo "<td>{$booking['username']}</td>";
                    echo "<td>{$booking['event_type']}</td>";
                    echo "<td>{$booking['event_place']}</td>";
                    echo "<td>{$booking['event_date']}</td>";
                    echo "<td>{$booking['status']}</td>"; 
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>