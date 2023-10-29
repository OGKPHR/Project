<?php
session_start();
require_once "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Check if the user exists
    $user_check_query = "SELECT * FROM user WHERE id = '$user_id' LIMIT 1";
    $result = mysqli_query($conn, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        // Delete the user
        $delete_query = "DELETE FROM user WHERE id = '$user_id'";
        $delete_result = mysqli_query($conn, $delete_query);

        if ($delete_result) {
            $_SESSION['success'] = "User deleted successfully";
            header("Location: UserManage.php"); // Redirect to user management page
            exit();
        } else {
            $_SESSION['error'] = "Error deleting user";
            header("Location: UserManage.php"); // Redirect to user management page
            exit();
        }
    } else {
        $_SESSION['error'] = "User not found";
        header("Location: UserManage.php"); // Redirect to user management page
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request";
    header("Location: UserManage.php"); // Redirect to user management page
    exit();
}
?>
