<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<!-- Form Container -->
<div class="container">
    <?php
    if (isset($_POST["submit"])) {
        $fullName = htmlspecialchars($_POST["full_name"]);
        $email = htmlspecialchars($_POST["email"]);
        $password = $_POST["password"];
        $passwordRepeat = $_POST["repeat_password"];
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $errors = array();

        // Form validation
        if (empty($fullName) || empty($email) || empty($password) || empty($passwordRepeat)) {
            array_push($errors, "All fields are required.");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid.");
        }
        if (strlen($password) < 8) {
            array_push($errors, "Password must be at least 8 characters long.");
        }
        if ($password !== $passwordRepeat) {
            array_push($errors, "Passwords do not match.");
        }

        // Display errors if any
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        } else {
            require_once "database.php";  

            
            $user_name = $fullName;
            $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);

            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "sss", $full_name, $email, $passwordHash);
                if (mysqli_stmt_execute($stmt)) {
                    echo "<div class='alert alert-success'>You are registered successfully.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Something went wrong with the SQL statement.</div>";
            }
        }
    }
    ?>

    <form action="regestration.php" method="POST">
        <div class="form-group">
            <input type="text" class="form-control" name="full_name" placeholder="Full Name:">
        </div> 

        <div class="form-group">
            <input type="email" class="form-control" name="email" placeholder="Email Address:">
        </div>
        
        <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Password:">
        </div>

        <div class="form-group">
            <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password">
        </div>
        
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Register" name="submit">
            <input type="reset" class="btn btn-danger" value="Cancel">
        </div>
    </form>
</div>

</body>
</html>