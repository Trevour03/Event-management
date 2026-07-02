<?php
// Include the database connection file
include 'db.php'; // Ensure this file has a proper connection to the database

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $venue_name = $conn->real_escape_string($_POST['venue_name']);
    $venue_address = $conn->real_escape_string($_POST['venue_address']);
    $venue_phone = $conn->real_escape_string($_POST['venue_phone']);
    $capacity = intval($_POST['capacity']);
    $preferred_for = $conn->real_escape_string($_POST['preferred_for']);
    $price = intval($_POST['price']);
    
    // Create the SQL query to insert the data into the venue table
    $sql = "INSERT INTO venue (venue_name, venue_address, venue_phone_no, capacity, preferred_for, price)
            VALUES ('$venue_name', '$venue_address', '$venue_phone', '$capacity', '$preferred_for', '$price')";
    
    // Execute the query and check for success
    if ($conn->query($sql) === TRUE) {
        echo "New venue added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Venue</title>
    <style>
        body {
            background-color: black;
            color: orange;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            display: flex; /* Use flexbox for the layout */
            max-width: 800px; /* Set a max width for the container */
            width: 100%;
        }
        .form-container {
            background-color: #2a2a2a;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            width: 100%;
            margin-right: 20px; /* Space between form and image */
        }
        .form-container h1 {
            text-align: center;
            color: orange;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            border: none;
            border-radius: 5px;
            background-color: #333;
            color: white;
        }
        .form-group input[type="text"], .form-group input[type="number"] {
            background-color: #444;
        }
        .form-group select {
            background-color: #444;
        }
        .image-container {
            flex: 1; /* Allow the image container to take up remaining space */
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .image-container img {
            max-width: 100%; /* Make the image responsive */
            height: auto; /* Maintain aspect ratio */
            border-radius: 10px; /* Rounded corners for the image */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Add Venue</h1>
            <form action="Add venue.php" method="post" >
                <div class="form-group">
                    <label for="venue_name">Venue Name:</label>
                    <input type="text" id="venue_name" name="venue_name" required>
                </div>
                <div class="form-group">
                    <label for="venue_address">Venue Address:</label>
                    <input type="text" id="venue_address" name="venue_address" required>
                </div>
                <div class="form-group">
                    <label for="venue_phone">Venue Phone No:</label>
                    <input type="text" id="venue_phone" name="venue_phone" required>
                </div>
                <div class="form-group">
                    <label for="capacity">Capacity:</label>
                    <input type="number" id="capacity" name="capacity" required>
                </div>
                <div class="form-group">
                    <label for="preferred_for">Preferred For:</label>
                    <select id="preferred_for" name="preferred_for" required>
                        <option value="">--Select--</option>
                        <option value="Graduation">Graduation</option>
                        <option value="Family Function">Family Function</option>
                        <option value="Birthday Party">Birthday Party</option>
                        <option value="Anniversary Party">Anniversary Party</option>
                        <option value="Farewell Party">Farewell Party</option>
                        <option value="College Event">College Event</option>
                        <option value="Crusade Event">Crusade Event</option>
                        <option value="Wedding">Wedding</option>
                        <option value="Conference">Conference</option>
                        <option value="Party">Party</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" required>
                </div>
                <div style="text-align: center;">
                    <button type="submit" style="background-color: orange; color: black; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Add</button>
                </div>
            </form>
        
    </div>

</body>
</html>
