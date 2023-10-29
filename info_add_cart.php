<?php
session_start();
require_once "connection.php";
echo $product_id;
if (isset($_SESSION['userid']) && isset($_GET['product_id'])) {
    $user_id = $_SESSION['userid'];
    $product_id = $_GET['product_id'];
  
    // Retrieve data sent via POST from the form
    $product_name = mysqli_real_escape_string($conn, $_POST["product_name"]);
    $product_price = floatval($_POST["product_price"]); // Ensure price is a floating-point number
    $provider_Id = intval($_POST["provider_Id"]); // Ensure provider ID is an integer

    // Check if the product is already in the cart
    $checkQuery = "SELECT * FROM cart_item WHERE product_id = $product_id AND user_id = $user_id";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "Product already in cart";
    } else {
        // Insert the product into the cart
        $insertQuery = "INSERT INTO cart_item (user_id, product_id) 
                        VALUES ($user_id, $product_id)";
        
        if (mysqli_query($conn, $insertQuery)) {
            // Redirect to the product_info.php page with a success message
            header("location: product_info.php?product_id=$product_id&added_to_cart=true");
            exit();
        } else {
            echo "Error adding to cart: " . mysqli_error($conn);
        }
    }
} else {
    echo "Error: Invalid request or user not logged in";
}
?>
