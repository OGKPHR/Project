<?php
session_start();
require_once "connection.php";

if (isset($_POST['remove_from_cart'])) {
    // Get the product ID to remove from the cart
    $product_id = $_POST['product_id'];

    // Get the user's ID from the session
    $user_id = $_SESSION['userid'];

    // Delete the product from the cart based on user ID and product ID
    $deleteQuery = "DELETE FROM cart WHERE user_id = $user_id AND product_id = $product_id";
    
    if (mysqli_query($conn, $deleteQuery)) {
        // Product removed successfully
        header("Location: cart_items.php");
        exit();
    } else {
        // Error occurred while removing the product
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // Redirect to the cart page if accessed without form submission
    header("Location: cart_items.php");
    exit();
}
?>
