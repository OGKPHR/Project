<?php
   session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "connection.php"; // Include your database connection
    
    $optionValueToAdd = $_POST["optionValue"];
    $tableName = $_POST["tableName"];
    
    // Perform input validation if needed
    
    // Insert the new option into the specified table
    $insertQuery = "INSERT INTO $tableName (option_value) VALUES ('$optionValueToAdd')";
    $insertResult = mysqli_query($conn, $insertQuery);
    
    if ($insertResult) {
        echo "Option added successfully!";
        
        
    } else {
        echo "Error adding option: " . mysqli_error($conn);
    }
    
    
}


?>
