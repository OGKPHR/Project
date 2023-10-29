<?php
// Include your database connection file
require_once "connection.php";

// Check if 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the transport provider's details by 'id'
    $query = "SELECT * FROM transport WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $transportname = $row['transportname'];
        $price = $row['price'];

        // Provide a form for editing
        echo "<h2>Edit Transport Provider</h2>";
        echo "<form method='post' action='update_transport.php'>";
        echo "<input type='hidden' name='id' value='$id'>";
        echo "<div class='form-group'>
                  <label for='transportname'>Transport Provider Name:</label>
                  <input type='text' class='form-control' name='transportname' value='$transportname' required>
                </div>";
        echo "<div class='form-group'>
                  <label for='price'>Price:</label>
                  <input type='number' class='form-control' name='price' value='$price' required>
                </div>";
        echo "<button type='submit' name='updateTransport' class='btn btn-primary'>Update Transport</button>";
        echo "</form>";
    } else {
        echo "Transport provider not found.";
    }
} else {
    echo "Invalid request.";
}
?>
