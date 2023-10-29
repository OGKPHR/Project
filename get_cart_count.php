<?php
session_start();

if (isset($_SESSION['userid'])) {
    require_once "connection.php";
    $user_id = $_SESSION['userid'];

    $cartCountQuery = "SELECT COUNT(*) as cartCount FROM cart_item WHERE user_id = $user_id";
    $cartCountResult = mysqli_query($conn, $cartCountQuery);

    if ($cartCountResult) {
        $cartCount = mysqli_fetch_assoc($cartCountResult)['cartCount'];

        // Return the cart count as JSON response
        echo $cartCount;
    } else {
        // Handle database query error
        echo json_encode(array('success' => false));
    }
} else {
    // Handle user not logged in
    echo json_encode(array('success' => false));
}
?>
