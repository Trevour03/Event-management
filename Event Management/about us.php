<?php
session_start(); // Start the session if needed

// Page title and content
$title = "About Us";
$content = "
    Welcome to our Event Management System! Our platform is dedicated to simplifying the process of planning and scheduling events. 
    Whether you are organizing a wedding, corporate event, or a private party, we provide the tools to help you find the perfect venue 
    and manage every aspect of your event seamlessly.
";

$mission = "Our mission is to empower users by providing an intuitive online platform that makes event planning effortless and enjoyable.";

$vision = "Our vision is to be the leading event management solution, recognized for our innovation, reliability, and outstanding customer service.";

$services = "
    Our platform offers a variety of features, including:
    <ul>
        <li>Search and book venues that fit your requirements and budget.</li>
        <li>Schedule events with a user-friendly calendar interface.</li>
        <li>Manage guest lists and send invitations directly.</li>
        <li>Access a range of services, from catering to decoration.</li>
        <li>Receive support from our dedicated event planning team.</li>
    </ul>
";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> About us></title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #8b5e3c;
        }
        h2 {
            color: #8b5e3c;
            margin-top: 20px;
        }
        p, ul {
            margin: 15px 0;
            font-size: 16px;
        }
        ul {
            list-style-type: disc;
            margin-left: 20px;
        }
        a {
            color: #8b5e3c;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h1><?php echo $title; ?></h1>
    <p><?php echo $content; ?></p>
    
    <h2>Our Mission</h2>
    <p><?php echo $mission; ?></p>

    <h2>Our Vision</h2>
    <p><?php echo $vision; ?></p>

    <h2>What We Offer</h2>
    <p><?php echo $services; ?></p>

    <h2>Contact Us</h2>
    <p>If you have any questions or would like to learn more about our services, feel free to <a href="contact us.php">contact us</a>.</p>
</div>

</body>
</html>
