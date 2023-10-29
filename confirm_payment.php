<?php
session_start();
require_once "connection.php";

if (isset($_GET['order_id'])) {
    $orderId = $_GET['order_id'];

    // Handle the button click to update the order status using AJAX
    if (isset($_GET['updateStatus']) && $_GET['updateStatus'] == '1') {
        $updateStatusQuery = "UPDATE order_table SET status_id = 1 WHERE id = $orderId";
        mysqli_query($conn, $updateStatusQuery);
        // Redirect to a success page or perform other actions after updating the status
        header("Location: Shop.php");
        exit();
    } elseif (isset($_GET['updateStatus']) && $_GET['updateStatus'] == '3') {
        $updateStatusQuery = "UPDATE order_table SET status_id = 3 WHERE id = $orderId";
        mysqli_query($conn, $updateStatusQuery);

        // Delete order_item and order_table if the countdown is complete
        if (isset($_GET['countdownExpired']) && $_GET['countdownExpired'] == '1') {
            $deleteOrderItemQuery = "DELETE FROM order_item WHERE order_id = $orderId";
            $deleteOrderTableQuery = "DELETE FROM order_table WHERE id = $orderId";

            mysqli_query($conn, $deleteOrderItemQuery);
            mysqli_query($conn, $deleteOrderTableQuery);
        }

        // Redirect to a success page or perform other actions after updating the status
        header("Location: Shop.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Confirm Payment</title>
    <script>
        // Set the countdown time to 2 minutes (in seconds)
        var countdownTime = 2 * 60;
        var status = 1; // Initial status

        // Update the countdown every second
        var countdown = setInterval(function () {
            // Calculate minutes and seconds
            var minutes = Math.floor(countdownTime / 60);
            var seconds = countdownTime % 60;

            // Display the countdown in the format MM:SS
            document.getElementById("countdown").innerHTML = minutes + "m " + seconds + "s ";

            // Check if the countdown has reached zero
            if (countdownTime <= 0) {
                // If the countdown has ended, change the status to 3 using an AJAX request
                // and set countdownExpired to 1 to delete order_item and order_table
                updateOrderStatus(3, 1);
                clearInterval(countdown);
            } else {
                // If the countdown is still running, decrement the time
                countdownTime--;
            }
        }, 1000); // Update every 1 second (1000 milliseconds)

        // Function to update order status using AJAX
        function updateOrderStatus(newStatus, countdownExpired) {
            var xhr = new XMLHttpRequest();
            var url = "confirm_payment.php?order_id=<?php echo $orderId; ?>&updateStatus=" + newStatus;

            if (countdownExpired === 1) {
                url += "&countdownExpired=1";
            }

            xhr.open("GET", url, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Redirect to Shop.php after updating the status
                    window.location.href = "Shop.php";
                }
            };
            xhr.send();
        }
    </script>
</head>

<body>
    <!-- Display order details -->

    <!-- Countdown timer -->
    <div id="countdown"></div>

    <!-- Button to update order status to 1 -->
    <form>
        <button type="button" onclick="updateOrderStatus(1)">Update Order Status to 1</button>
    </form>
</body>

</html>