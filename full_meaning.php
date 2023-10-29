<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Full Meanings</title>
    <!-- Add your CSS styles here if needed -->
</head>
<body>
    <?php
    // Include your database connection code here
    $conn = mysqli_connect("localhost", "root", "", "loginadminuser");

    if (!$conn) {
        die("Failed to connect to database " . mysqli_error($conn));
    }

    // Retrieve URL parameters
    $phone = isset($_GET['phone']) ? $_GET['phone'] : '';
    $pair = isset($_GET['pair']) ? $_GET['pair'] : '';

    // Fetch and display the full meanings for the selected phone number and pair
    $meaningQuery = "SELECT Pair, Meaning FROM numbermeanings WHERE RIGHT('$phone', 2) = Pair LIMIT 4";
    $meaningResult = mysqli_query($conn, $meaningQuery);

    if ($meaningResult) {
        while ($meaningRow = mysqli_fetch_assoc($meaningResult)) {
            $pair = $meaningRow['Pair'];
            $meaning = $meaningRow['Meaning'];

            echo '<p><strong>Pair: ' . $pair . '</strong><br>';
            echo '<strong>Meaning: </strong>' . $meaning . '</p>';
        }
    } else {
        echo "Error fetching meanings: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
    ?>

    <!-- Add your HTML structure and styling here as needed -->
</body>
</html>
