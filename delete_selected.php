<?php
require_once "connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_files'])) {
    $selectedFiles = $_POST['selected_files'];
    
    foreach ($selectedFiles as $filename) {
        $filepath = 'fileupload/' . $filename;
        
        if (unlink($filepath)) {
            $query = "DELETE FROM uploadfile WHERE fileupload = '$filename'";
            
            if (!$conn->query($query)) {
                echo "Error deleting picture: " . $conn->error;
            }
        } else {
            echo "Error deleting picture file: " . $filename;
        }
    }
    
    header("Location: banner.php"); // Redirect back to the page after deletion
    exit();
}
?>
