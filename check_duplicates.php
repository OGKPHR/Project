<?php
require_once "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    
    $errors = [
        'username' => '',
        'email' => ''
    ];

    if (strlen($username) >= 5 && strlen($username) <= 12) {
        $username_query = "SELECT * FROM user WHERE username = '$username'";
        $username_result = mysqli_query($conn, $username_query);

        if (mysqli_num_rows($username_result) > 0) {
            $errors['username'] = 'duplicate';
        }
    } else {
        $errors['username'] = 'length';
    }

    if (!empty($email)) {
        $email_query = "SELECT * FROM user WHERE email = '$email'";
        $email_result = mysqli_query($conn, $email_query);

        if (mysqli_num_rows($email_result) > 0) {
            $errors['email'] = 'duplicate';
        }
    }

    echo json_encode($errors);
}
?>
