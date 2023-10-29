<?php
session_start();
require_once "connection.php";

// Initialize the error message variable
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if the email exists in the user table
    $checkEmailQuery = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($conn, $checkEmailQuery);

    if (mysqli_num_rows($result) > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(32));

        // Store the token and user_id in the password_reset_requests table
        $userRow = mysqli_fetch_assoc($result);
        $userId = $userRow['id'];

        $timestamp = time(); // Current timestamp

        $insertTokenQuery = "INSERT INTO password_reset_requests (user_id, email, token, timestamp) VALUES ('$userId', '$email', '$token', '$timestamp')";
        if (mysqli_query($conn, $insertTokenQuery)) {
            // Send an email with a link containing the token
            $resetLink = "http://localhost/loginadminuser(1)/loginadminuser/resetpassword.php?token=$token";
            $emailSubject = "Password Reset";
            $emailBody = "To reset your password, click on the following link: $resetLink";
            
            // TODO: Use PHP's mail() function or a third-party library to send the email

            // Redirect the user to a confirmation page
            header("Location: forgotpassconfirmation.php");
            exit();
        } else {
            $errorMessage = "Error inserting token into the database: " . mysqli_error($conn);
        }
    } else {
        $errorMessage = "Email not found. Please check your email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <?php include('newnav.php'); ?>
    <main>
        <h2>Forgot Password</h2>
        <form method="post">
            <label for="email">Email:</label>
            <input type="email" name="email" required><br>
            <input type="submit" value="Reset Password">
        </form>

        <?php
        if (!empty($errorMessage)) {
            echo '<p class="error-message">' . $errorMessage . '</p>';
        }
        ?>
    </main>
    <footer>
        <!-- Include your footer content here -->
    </footer>
</body>
</html>
