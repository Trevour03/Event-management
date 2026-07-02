<?php
session_start(); // Start the session if needed

// Page title and content
$title = "Contact Us";
$phone1 = "+254702653112";
$phone2 = "0780845683";
$whatsapp = "0780845683";
$email = "paultrevour03@gmail.com";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
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
        .contact-item {
            display: flex;
            align-items: center;
            margin: 15px 0;
            font-size: 18px;
        }
        .contact-item i {
            font-size: 24px;
            color: #8b5e3c; /* Icon color */
            margin-right: 10px;
        }
        .contact-item a {
            color: #333; /* Link color */
            text-decoration: none;
        }
        .contact-item a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h1><?php echo $title; ?></h1>
    <div class="contact-item">
        <i class="fas fa-phone"></i>
        <span>Phone: <a href="tel:<?php echo $phone1; ?>"><?php echo $phone1; ?></a>, <a href="tel:<?php echo $phone2; ?>"><?php echo $phone2; ?></a></span>
    </div>
    <div class="contact-item">
        <i class="fab fa-whatsapp"></i>
        <span>WhatsApp: <a href="https://wa.me/<?php echo $whatsapp; ?>" target="_blank"><?php echo $whatsapp; ?></a></span>
    </div>
    <div class="contact-item">
        <i class="fas fa-envelope"></i>
        <span>Email: <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></span>
    </div>
</div>

</body>
</html>
