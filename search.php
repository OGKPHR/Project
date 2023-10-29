<?php
require_once "connection.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $positions = $_POST["positions"];
    $conditions = [];

    // Construct conditions for each specified position
    foreach ($positions as $index => $value) {
        if (!empty($value)) {
            $conditions[] = "SUBSTRING(phone_number, " . ($index + 1) . ", 1) = '$value'";
        }
    }

    // Check if network provider is selected
    if (!empty($_POST["Provider"])) {
        $provider = mysqli_real_escape_string($conn, $_POST["Provider"]);
        // Get provider_id based on selected provider_name
        $providerQuery = "SELECT id FROM providers WHERE provider_name = '$provider'";
        $providerResult = mysqli_query($conn, $providerQuery);
        if ($providerRow = mysqli_fetch_assoc($providerResult)) {
            $providerId = $providerRow['id'];
            $conditions[] = "provider_id = $providerId";
        } else {
            // Handle error if provider is not found
        }
    }

    // Check if price range is specified
    if (isset($_POST["price_low"]) && isset($_POST["price_high"])) {
        $priceLow = intval($_POST["price_low"]);
        $priceHigh = intval($_POST["price_high"]);
        if ($priceLow >= 0 && $priceHigh > 0 && $priceLow <= $priceHigh) {
            $conditions[] = "(selling_price BETWEEN $priceLow AND $priceHigh)";
        }
    }

    if (isset($_POST["Set_of_numbers"]) && !empty($_POST["Set_of_numbers"])) {
        $setOfNumbersInput = $_POST["Set_of_numbers"];
        $setOfNumbers = explode(",", $setOfNumbersInput);
        $numberConditions = [];
        foreach ($setOfNumbers as $number) {
            $number = mysqli_real_escape_string($conn, trim($number));
            $numberConditions[] = "SUBSTRING(phone_number, -7) LIKE '%$number%'";

        }
        // Add number conditions to the main conditions array
        if (!empty($numberConditions)) {
            $conditions[] = "(" . implode(" OR ", $numberConditions) . ")";
        }
    } else {
        echo "ไม่เข้าเงื่อนไขชุดเลข";
    }
    if (isset($_POST["selected_numbers"]) && !empty($_POST["selected_numbers"])) {
        $selected_numbers = $_POST["selected_numbers"];
        $selectofnumber = explode(",", $selected_numbers);
        $numberConditions = [];
        foreach ($selectofnumber as $number) {
            $number = mysqli_real_escape_string($conn, trim($number));
            $numberConditions[] = "SUBSTRING(phone_number, -7) LIKE '%$number%'";
        }
        // Add number conditions to the main conditions array
        if (!empty($numberConditions)) {
            $conditions[] = "(" . implode(" OR ", $numberConditions) . ")";
        }
    } else {
        echo "ไม่เข้าเงื่อนไขชุดเลข";
    }
    if (isset($_POST["disliked_numbers"]) && !empty($_POST["disliked_numbers"])) {
        $dislikedNumbers = $_POST["disliked_numbers"];
        $dislikedNumbersArray = explode(",", $dislikedNumbers);
        $dislikedNumberConditions = [];
        foreach ($dislikedNumbersArray as $number) {
            $number = mysqli_real_escape_string($conn, trim($number));
            // ไม่ต้องการเลขที่ไม่ชอบ
            $dislikedNumberConditions[] = "SUBSTRING(phone_number, -7) NOT LIKE '%$number%'";
        }

        // เพิ่มเงื่อนไขใน WHERE clause
        if (!empty($dislikedNumberConditions)) {
            $conditions[] = "(" . implode(" AND ", $dislikedNumberConditions) . ")";
        }
    }
    // ตรวจสอบว่ามีข้อมูลที่เก็บไว้ใน session หรือไม่
    if (isset($_SESSION['selected_numbers'])) {
        $selectedNumbers = $_SESSION['selected_numbers'];
    } else {
        echo "ไม่มีข้อมูลที่เก็บไว้ใน session";
    }


    // Create the SQL query with dynamic conditions
    $query = "SELECT p.*
              FROM product p
              LEFT JOIN order_item oi ON p.id = oi.product_id
              LEFT JOIN order_table ot ON oi.order_id = ot.id
              WHERE (oi.id IS NULL OR (ot.status_id = 3 AND oi.product_id = p.id))";

    // Add conditions to the query if any positions, provider, or price range were specified
    if (!empty($conditions)) {
        $query .= " AND (" . implode(" AND ", $conditions) . ")";
    }

    // Execute the query and fetch results
    $result = mysqli_query($conn, $query);

    // Rest of your code to display products...
    // Example:
    var_dump($_POST["price_low"]);
    var_dump($_POST["price_high"]);
    var_dump($_POST["Set_of_numbers"]);
    echo "เลขที่ชอบ" . $_POST["selected_numbers"];
    echo $query;
    while ($row = mysqli_fetch_assoc($result)) {
        // Display products based on search results
        // You can customize how the products are displayed here
        echo "Product Name: " . $row['phone_number'] . "<br>";
    }
}
?>