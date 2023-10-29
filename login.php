<?php
session_start();

if (isset($_POST['username'])) {
    include('connection.php');

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM user WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);

        if (password_verify($password, $row['password'])) {
            $_SESSION['userid'] = $row['id'];
            $_SESSION['user'] = $row['firstname'] . " " . $row['lastname'];
            $_SESSION['fname'] = $row['firstname'];
            $_SESSION['userlevel'] = $row['userlevel'];
            
            if ($_SESSION['userlevel'] == 'a') {
                header("Location: Addproduct.php");
            }

            if ($_SESSION['userlevel'] == 'm') {
                header("Location: Shop.php");
            }
        } else {
            // Password is incorrect, set an error message
            $_SESSION['error_message'] = 'User or Password is incorrect';
            header("Location: index.php");
        }
    } else {
        // User not found, set an error message
        $_SESSION['error_message'] = 'User or Password is incorrect';
        header("Location: index.php");
    }
} else {
    header("Location: index.php");
}
?>
