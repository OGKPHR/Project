<?php
require_once "connection.php"; // Include your database connection file

session_start();

if (!isset($_SESSION['userid'])) { // Check if the session variable 'userid' is not set
    header("Location: index.php");
    exit(); // Make sure to exit after the redirect
}

$isInCart = false; // Initialize the $isInCart variable to false

// Check if a product ID is provided in the URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch product details including provider information
    $productQuery = "SELECT p.*, pr.option_value AS provider_name, pr.providerlogo AS provider_image, p.price AS product_price
                 FROM product p
                 LEFT JOIN providers pr ON p.provider_id = pr.id
                 WHERE p.id = $product_id";

    $productResult = mysqli_query($conn, $productQuery);

    // Check if the product was found
    if (mysqli_num_rows($productResult) > 0) {
        $productData = mysqli_fetch_assoc($productResult);

        // Extract phone number and remove the first 3 digits
        $phone_number = $productData['phonenumber'];
        $phoneNumberWithoutFirstThreeDigits = substr($phone_number, 3);

        // Split the remaining phone number into pairs
        $pairs = [];
        for ($i = 0; $i < strlen($phoneNumberWithoutFirstThreeDigits) - 1; $i++) {
            $pair = substr($phoneNumberWithoutFirstThreeDigits, $i, 2);
            $pairs[] = $pair;
        }

        // Calculate the average scores for each category across all pairs
        $averageScores = array(
            'finance' => 0,
            'work' => 0,
            'fortune' => 0,
            'love' => 0,
            'health' => 0,
            'utterance' => 0,
            'mind' => 0,
            'charm' => 0,
            'personality' => 0,
            'learning' => 0
        );

        $totalPairs = count($pairs);

        foreach ($pairs as $pair) {
            $pairMeaningQuery = "SELECT Pair, Meaning, finance, work, fortune, love, health, utterance, mind, charm, personality, learning FROM numbermeanings WHERE Pair = '$pair'";
            $pairMeaningResult = mysqli_query($conn, $pairMeaningQuery);
            $pairMeaning = mysqli_fetch_assoc($pairMeaningResult);

            if ($pairMeaning) {
                $averageScores['finance'] += $pairMeaning['finance'] / $totalPairs;
                $averageScores['work'] += $pairMeaning['work'] / $totalPairs;
                $averageScores['fortune'] += $pairMeaning['fortune'] / $totalPairs;
                $averageScores['love'] += $pairMeaning['love'] / $totalPairs;
                $averageScores['health'] += $pairMeaning['health'] / $totalPairs;
                $averageScores['utterance'] += $pairMeaning['utterance'] / $totalPairs;
                $averageScores['mind'] += $pairMeaning['mind'] / $totalPairs;
                $averageScores['charm'] += $pairMeaning['charm'] / $totalPairs;
                $averageScores['personality'] += $pairMeaning['personality'] / $totalPairs;
                $averageScores['learning'] += $pairMeaning['learning'] / $totalPairs;
            }
        }

      // Check if the product is in the cart
if (isset($_SESSION['userid'])) {
    $user_id = $_SESSION['userid'];

    $checkQuery = "SELECT * FROM cart_item WHERE product_id = $product_id AND user_id = $user_id";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        $isInCart = true;
    }
}

    } else {
        // Handle the case when the product with the provided ID was not found
        echo "Product not found.";
        exit();
    }
} else {
    // Handle the case when no product ID is provided
    echo "Product ID not provided.";
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Lucky Phone Number Shop</title>
    <link rel="icon" href="unnamed.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- Bootstrap JS (jQuery is a prerequisite) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="path/to/jquery.min.js"></script>
<script src="path/to/popper.min.js"></script>
<script src="path/to/bootstrap.min.js"></script>

    <link rel="stylesheet" href="shop.css">
    <style> .btn-gradient-border {
            color: rgb(var(--text-color));
            border: 2px double transparent;
            background-image: linear-gradient(rgb(13, 14, 33), rgb(13, 14, 33)),
                radial-gradient(circle at left top, rgb(1, 110, 218), rgb(217, 0, 192));
            background-origin: border-box;
            background-clip: padding-box, border-box;
        }

        .text-glow {
            text-shadow: 0 0 80px rgb(192 219 255 / 75%), 0 0 32px rgb(65 120 255 / 24%);
        }

        .text-gradient {
            background: linear-gradient(to right, #30CFD0, #c43ad6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        /* Default styles for larger screens */
        body {
            padding-top: 0px;
            background-color: black;
            color: white;
        }

        /* Styles for smaller screens (e.g., smartphones) */
        @media (max-width: 767px) {
            body {
                padding-top: 10px;
                /* Add more top padding for better spacing on small screens */
            }

            /* Add more specific styles for smaller screens here */
        }

        /* Styles for medium-sized screens (e.g., tablets) */
        @media (min-width: 768px) and (max-width: 991px) {
            /* Add styles for medium-sized screens here */
        }

        .chart-container {
            width: 50%;
        }

        .meaning {
            max-width: 80%;
            margin: auto;
        }

        /* CSS to move the cart icon to the right corner */
        .cart-icon.cart-icon-right {
            position: absolute;
            top: 50%;
            /* Adjust the top position as needed */
            right: 250px;
            /* Adjust the right position as needed */
        }
    </style>
</head>

<body style="padding-top: 0px;background-color: #111;color: white;">

    <?php include('newnav.php'); ?>
    <main>
        <div class="content" style="padding-top:200px;">

            <!-- Display product information -->

            <div class="product" style="margin: auto; min-width: 300px; max-width: 300px; max-height: fit-content; border-radius: 20px;">
    <?php $totalScores = array_sum($averageScores) / 10 ?>
    <!-- Display the provider's picture -->
    <img style="width: 80px;" src="provider/<?php echo $productData['provider_image']; ?>" alt="<?php echo $productData['provider_name']; ?>">
   
   <?php

    // Define the grade logic based on avg_score
    $avgScore = number_format($totalScores, 0);
    $gradeText = '';

    if ($avgScore >= 80 && $avgScore <= 100) {
        $gradeText = 'A';
    } elseif ($avgScore >= 75 && $avgScore <= 79) {
        $gradeText = 'B+';
    } elseif ($avgScore >= 70 && $avgScore <= 74) {
        $gradeText = 'B';
    } elseif ($avgScore >= 65 && $avgScore <= 69) {
        $gradeText = 'C+';
    } elseif ($avgScore >= 60 && $avgScore <= 64) {
        $gradeText = 'C';
    } elseif ($avgScore >= 55 && $avgScore <= 59) {
        $gradeText = 'D+';
    } elseif ($avgScore >= 50 && $avgScore <= 54) {
        $gradeText = 'D';
    } else {
        $gradeText = 'F';
    }
    ?>
    <h6>คะแนน: <?php echo $avgScore ?></h6>
    <h3 style="position: absolute; top: 1; right: 4; font-weight: bolder; font-style: italic; color: greenyellow; text-align: center; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;"><?php echo $gradeText; ?></h3>
    <div class="text" style="text-align: center; font-family: Arial, Helvetica, Arial, Helvetica, sans-serif;">
        <h2 style="display: inline-block; "><?php echo $productData['phonenumber']; ?></h2>
    </div>
     <!-- Display the product price -->
     <h6>ราคา: <?php echo number_format($productData['product_price']); ?> บาท</h6>
       
    <div class="text" style="text-align: right;">
        <form method="post" action="info_add_cart.php?product_id=<?php echo $product_id; ?>">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            <input type="hidden" name="product_name" value="<?php echo $productData['phonenumber']; ?>">
            <input type="hidden" name="product_price" value="<?php echo$productData['product_price'];  ?>"> <!-- Display the product price -->
            <input type="hidden" name="provider_Id" value="<?php echo $productData['provider_id']; ?>">
            <?php if ($isInCart): ?>
                <button class="btn btn-gradient-border" disabled>Added to Cart</button>
            <?php else: ?>
                <button class="btn btn-gradient-border btn-glow" type="submit" name="add_to_cart">Add to Cart</button>
            <?php endif; ?>
        </form>
    </div>
</div>


            <div style="display: flex;">
                <!-- Create a container for the bar charts -->
                <div class="chart-container">
                    <canvas id="score-chart" width="80%" height="200px"></canvas>
                </div>
                <div class="chart-container">
                    <canvas id="score-rachart" width="80%" height="400px"></canvas>
                </div>
            </div>

            <div class="meaning">
                <h3>ทำนายเบอร์</h3>
                <ul>
                    <?php
                    $phone_number = $productData['phonenumber'];
                    $phoneNumberWithoutFirstThreeDigits = substr($phone_number, 3);

                    $markedPairs = []; // Keep track of marked pairs

                    // Ensure you have at most 6 pairs
                    for ($i = 0; $i < min(6, strlen($phoneNumberWithoutFirstThreeDigits) - 1); $i++) {
                        $pair = substr($phoneNumberWithoutFirstThreeDigits, $i, 2);

                        // Check if the pair is valid (e.g., it doesn't contain pairs within it) and hasn't been marked before
                        $isValidPair = !in_array($pair, $markedPairs);

                        if ($isValidPair) {
                            $markedPairs[] = $pair; // Mark the pair
                            $pairMeaningQuery = "SELECT Pair, Meaning FROM numbermeanings WHERE Pair = '$pair'";
                            $pairMeaningResult = mysqli_query($conn, $pairMeaningQuery);
                            $pairMeaning = mysqli_fetch_assoc($pairMeaningResult);

                            if ($pairMeaning) {
                                echo '<li>';
                                echo '<h4>' . preg_replace("/($pair)/", "<span style='color: gold;'>$pair</span>", $phone_number) . '</h4>' . '<br>';
                                echo 'ความหมาย ' . '<h5 style="color:yellow;">' . $pair . '</h5>' . '<h5>' . $pairMeaning['Meaning'] . '</h5>';
                                echo '</li>';
                                echo '<hr style="border: none; height:2px; background-color: white;">'; // Add height and background color
                            }
                        }
                    }
                    ?>
                </ul>

            </div>
        </div>

    </main>
</body>

</html>
<script src="cartcount.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data for the bar chart
    var scoresData = {
        labels: ['Finance', 'Work', 'Fortune', 'Love', 'Health', 'Utterance', 'Mind', 'Charm', 'Personality', 'Learning'],
        datasets: [{
            label: 'Average Scores',
            data: [
                <?php echo $averageScores['finance']; ?>,
                <?php echo $averageScores['work']; ?>,
                <?php echo $averageScores['fortune']; ?>,
                <?php echo $averageScores['love']; ?>,
                <?php echo $averageScores['health']; ?>,
                <?php echo $averageScores['utterance']; ?>,
                <?php echo $averageScores['mind']; ?>,
                <?php echo $averageScores['charm']; ?>,
                <?php echo $averageScores['personality']; ?>,
                <?php echo $averageScores['learning']; ?>
            ],
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    };

    // Create a bar chart
    var ctx = document.getElementById('score-chart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: scoresData,
        options: {
            responsive: true, // Enable responsiveness
            maintainAspectRatio: false, // Allow aspect ratio to be flexible
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
</script>

   
<script>
    // Create a radar chart
    var ctx = document.getElementById('score-rachart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'radar',
        data: {
            labels: ['Finance', 'Work', 'Fortune', 'Love', 'Health', 'Utterance', 'Mind', 'Charm', 'Personality', 'Learning'],
            datasets: [{
                label: 'Average Scores',
                data: [
                    <?php echo $averageScores['finance']; ?>,
                    <?php echo $averageScores['work']; ?>,
                    <?php echo $averageScores['fortune']; ?>,
                    <?php echo $averageScores['love']; ?>,
                    <?php echo $averageScores['health']; ?>,
                    <?php echo $averageScores['utterance']; ?>,
                    <?php echo $averageScores['mind']; ?>,
                    <?php echo $averageScores['charm']; ?>,
                    <?php echo $averageScores['personality']; ?>,
                    <?php echo $averageScores['learning']; ?>
                ],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true, // Enable responsiveness
            maintainAspectRatio: false, // Allow aspect ratio to be flexible
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
</script>
</main>
<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="color: black;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<footer>
    <p>&copy; 2023 Lucky Phone Number Shop. All rights reserved.</p>
</footer>
<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript -->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script>
    window.onscroll = function () { myFunction() };

    var navbar = document.getElementById("navbar");
    var sticky = navbar.offsetTop;

    function myFunction() {
        if (window.pageYOffset >= sticky) {
            navbar.classList.add("sticky")
        } else {
            navbar.classList.remove("sticky");
        }
    }
</script>
</body>

</html>
