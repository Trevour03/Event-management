<?php
session_start();
include 'db.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: LOG IN.php");
    exit();
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        /* General styling */
        body {
            background-image: url('image/evnt.png'); 
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            color: brown;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .navbar {
            background-color: rgba(0, 0, 0, 0.8); 
            padding: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center; 
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 15px;
            border-radius: 5px;
        }

        .navbar a:hover {
            background-color: #333;
        }

        /* Profile icon styling */
        .profile-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%; 
            overflow: hidden; 
            cursor: pointer; /* Make icon clickable */
        }

        .profile-icon img {
            width: 100%; 
            height: auto;
        }

        /* Modal styling */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            width: 80%;
            max-width: 400px;
        }

        .modal-content h2 {
            margin-top: 0;
        }

        .close-btn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-btn:hover {
            color: black;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="nav-links">
            <a href="my details.php">My Details</a>
            <a href="Book Event.php">Book an Event</a>
            <a href="Booking status.php">Booking Status</a>
            <a href="Payment.php">Payment</a>
            <a href="Booking history.php">Booking History</a>
            <a href="feedback.php">Feedback</a>
            <a href="view venue.php">View Venue</a>
            <a href="log out.php">Logout</a>
        </div>
        
        <!-- Profile Icon -->
        <div class="profile-icon" onclick="openModal()">
            <img src="image/profile.png" alt="User Profile"> 
        </div>
    </div>

    <div class="content">
        <h1>Welcome to Your EVENT MANAGEMENT Dashboard</h1>
        <p>Here you can manage your bookings, view details, and more.</p>
    </div>

    <!-- Modal for User Details -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>User Profile</h2>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        </div>
    </div>

    <script>
        // Function to open the modal
        function openModal() {
            document.getElementById('userModal').style.display = 'flex';
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById('userModal').style.display = 'none';
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('userModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>

</html>
