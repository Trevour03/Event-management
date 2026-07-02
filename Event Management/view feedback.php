<?php
// Start the session
session_start();

// Include the database connection file
include('db.php'); // Assuming your database connection is in db.php

// Query to fetch feedback from the feedback table
$sql = "SELECT * FROM feedback ORDER BY created_at DESC"; // Order feedback by date (most recent first)
$result = $conn->query($sql);

// Check if there are any feedback records
if ($result->num_rows > 0) {
    // Start the feedback container
    echo '<div class="feedback-container">';
    
    // Loop through the feedback records
    while ($row = $result->fetch_assoc()) {
        // Display each feedback
        echo '<div class="feedback-item">';
        echo '<h3>' . htmlspecialchars($row['user_name']) . '</h3>'; // User's name
        echo '<p>' . htmlspecialchars($row['feedback']) . '</p>'; // Feedback text
        echo '<small>Posted on: ' . $row['created_at'] . '</small>'; // Date feedback was posted
        echo '</div>';
    }
    
    // End the feedback container
    echo '</div>';
} else {
    // If no feedback found
    echo '<p>No feedback available.</p>';
}

// Close the database connection
$conn->close();
?>

<!-- HTML to display feedback -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>
    <style>
        /* Add your CSS styles here */
        body {
            background-image: url('image/vnw.jpg'); 
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .feedback-container {
            width: 80%;
            margin: 0 auto;
        }
        .feedback-item {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
        }
        .feedback-item h3 {
            color: #333;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .feedback-item p {
            font-size: 16px;
            color: #555;
        }
        .feedback-item small {
            color: #999;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <h1>View Feedback</h1>
    <?php
    // Display feedback
    
    ?>
</body>
</html>
