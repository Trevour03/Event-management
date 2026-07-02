
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN Dashboard</title>
    <!-- Link to Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Set background image */
        body {
            background-image: url('image/admin\ bkg.png'); 
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            color: darksalmon;
            margin: 0;
            padding: 0;
        }

        /* Navigation bar styling */
        .navbar {
            background-color: rgba(0, 1, 0, 0.8); 
            padding: 25px; /* Fixed comment */
            display: flex;
            justify-content: space-between; 
            align-items: center; 
        }

        .navbar a {
            color: blue;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 15px;
            border-radius: 5px;
            position: relative; /* To position the notification bubble */
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
            cursor: pointer;
        }

        .profile-icon img {
            width: 100%; 
            height: auto;
        }

        /* Style for the message icon */
        .message-icon {
            color: green;
            font-size: 20px;
            margin-left: 10px;
            position: relative;
        }

        .message-count {
            background-color: red;
            color: white;
            padding: 2px 8px;
            border-radius: 50%;
            font-size: 12px;
            position: absolute;
            top: 0;
            right: 0;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="nav-links">
            <a href="Add venue.php" class="nav-button">Add Venue</a>
            <a href="view Bookings.php" class="nav-button">View Bookings</a>
            <a href="view feedback.php" class="nav-button">View Feedback</a>
            
            <!-- Notifications/Alerts link with message icon -->
            <a href="Notifications.php" class="nav-button">
                Notifications/Alerts
                <span class="message-icon">
                    <i class="fas fa-envelope"></i> <!-- Message icon -->
                    <span class="message-count">2</span> <!-- Notification count (example) -->
                </span>
            </a>
            
            <a href="Upcoming Events.php" class="nav-button">Upcoming Event</a>
            <a href="log out.php" class="nav-button">Log out</a>
        </div>
        <!-- Profile Icon -->
        <div class="profile-icon">
            <img src="image/adm.png" alt="Admin Profile"> 
        </div>
    </div>

    <div class="content">
        <h1>ADMINS Dashboard</h1>
        <p>SYSTEM SETTINGS AND MANAGEMENT IS DONE HERE.</p>
    </div>
    
</body>
</html>