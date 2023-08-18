<?php
require_once "connection.php"; // Include your database connection script

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $optionValue = $_POST["optionValue"];
    $tableName = $_POST["tableName"];
    
    // Perform input validation if needed
    
    // Delete the option from the specified table
    $sql = "DELETE FROM $tableName WHERE option_value = '$optionValue'";
    
    // Execute the SQL query using your database connection
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "Option deleted successfully!";
    } else {
        echo "Error deleting option: " . mysqli_error($conn);
    }
}
?>
