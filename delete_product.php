<?php
session_start();
require_once "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch product details from the database based on the $product_id
    $product_query = "SELECT * FROM product WHERE id = $product_id";
    $product_result = mysqli_query($conn, $product_query);

    if (mysqli_num_rows($product_result) > 0) {
        $product = mysqli_fetch_assoc($product_result);

        // Delete product from the database
        $delete_query = "DELETE FROM product WHERE id = $product_id";
        $delete_result = mysqli_query($conn, $delete_query);

        if ($delete_result) {
            $_SESSION['success'] = "Product deleted successfully.";
        } else {
            $_SESSION['error'] = "Failed to delete product.";
        }
    } else {
        $_SESSION['error'] = "Product not found.";
    }

    header("Location: Addproduct.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: Addproduct.php");
    exit();
}
