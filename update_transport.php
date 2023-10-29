<?php
// Include your database connection file
require_once "connection.php";

if (isset($_POST['updateTransport'])) {
    $id = $_POST['id'];
    $transportname = $_POST['transportname'];
    $price = $_POST['price'];

    // Perform necessary validation on $transportname and $price

    // Update the transport provider's information in the database
    $query = "UPDATE transport SET transportname = '$transportname', price = $price WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: transportManage.php"); // Redirect to the transport management page after updating
        exit;
    } else {
        // Handle the error, e.g., display an error message
        echo "Error updating transport: " . mysqli_error($conn);
    }
}
