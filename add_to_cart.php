<?php
session_start();
require_once "connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['userid'];

    $product_id = $_POST["product_id"];

    $checkQuery = "SELECT * FROM cart_item WHERE product_id = $product_id AND user_id = $user_id";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "Product already in cart";
    } else {
        $insertQuery = "INSERT INTO cart_item (user_id, product_id) VALUES ($user_id, $product_id)";
        if (mysqli_query($conn, $insertQuery)) {
            echo "Product added to cart successfully";
        } else {
            echo "Error adding product to cart: " . mysqli_error($conn);
        }
    }
} else {
    echo "Invalid request method";
}
?>
