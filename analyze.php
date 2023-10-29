<?php
require_once "connection.php"; // Include your database connection file
require_once "calculate.php"; // Include calculate.php

// Check if a phone number is provided in the URL
if (isset($_GET['phone_number'])) {
    $phone_number = $_GET['phone_number'];

    // Remove any non-numeric characters from the phone number
    $phone_number = preg_replace('/[^0-9]/', '', $phone_number);

    // Call the calculateAverageScoreAndPrice function to get the average score
    $result = calculateAverageScoreAndPrice($phone_number, $conn);
    $averageScore = $result['averageScore'];

    // Format the average score to display with 1 decimal place
    $averageScoreFormatted = number_format($averageScore, 0);

    // Define the grade logic based on the formatted average score
    $gradeText = '';

    if ($averageScoreFormatted >= 80) {
        $gradeText = 'A';
    } elseif ($averageScoreFormatted >= 75) {
        $gradeText = 'B+';
    } elseif ($averageScoreFormatted >= 70) {
        $gradeText = 'B';
    } elseif ($averageScoreFormatted >= 65) {
        $gradeText = 'C+';
    } elseif ($averageScoreFormatted >= 60) {
        $gradeText = 'C';
    } elseif ($averageScoreFormatted >= 55) {
        $gradeText = 'D+';
    } elseif ($averageScoreFormatted >= 50) {
        $gradeText = 'D';
    } else {
        $gradeText = 'F';
    }
  
    // Extract phone number and remove the first 3 digits
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
} else {
    // Handle the case when no phone number is provided
    echo "Phone number not provided.";
    exit();
}
session_start();

if (!isset($_SESSION['userid'])) { // Check if the session variable 'userid' is not set
    header("Location: index.php");
    exit(); // Make sure to exit after the redirect
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

    <link rel="stylesheet" href="shop.css">
</head>
<style>/* Default styles for larger screens */
body {
    padding-top: 0px;
    background-color: black;
    color: white;
}

/* Styles for smaller screens (e.g., smartphones) */
@media (max-width: 767px) {
    body {
        padding-top: 10px; /* Add more top padding for better spacing on small screens */
    }
    /* Add more specific styles for smaller screens here */
}

/* Styles for medium-sized screens (e.g., tablets) */
@media (min-width: 768px) and (max-width: 991px) {
    /* Add styles for medium-sized screens here */
}
.chart-container{
    width: 50%;
}

.meaning{
    max-width: 80%;
    margin: auto;
}
.analysis-box {
    border: 1px solid white;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    margin: 0 auto; /* This centers the box horizontally */
    max-width: 400px; /* Optional: Set a maximum width for the box */
    
    margin-top: 20px; /* Optional: Add some top margin */
}

</style>
<body style="padding-top: 0px;background-color: #111;color: white;">
<?php include('newnav.php'); ?>
    </div>
    <main >
    <div class="content" style="padding-top:200px;">
   
    <div class="analysis-box">
    <h1>ผลการวิเคราะห์</h1>
    <h2>คะแนน: <?php echo $averageScoreFormatted; ?></h2>
    <p>หมายเลข: <?php echo $phone_number; ?></p>
    <h3 style="display: inline;">เกรด:</h3>
<h3 style="display: inline; font-weight: bolder; font-style: italic; color: greenyellow; text-align: center; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;"><?php echo $gradeText; ?></h3>
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
            $phone_number = $_GET['phone_number'];
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
                        echo   '<h4>' . preg_replace("/($pair)/", "<span style='color: gold;'>$pair</span>", $phone_number) . '</h4>' . '<br>';
                        echo 'ความหมาย '.'<h5 style="color:yellow;">'.$pair .'</h5>'.'<h5>'. $pairMeaning['Meaning'].'</h5>';
                        echo '</li>';
                        echo '<hr style="border: none; height: 1px; background-color: white;">'; // Add height and background color
                    }
                }
            }
            ?>
        </ul>
    </div>
</div>
</body>
</html>

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
    var ctx = document.getElementById('score-rachart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'radar',
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
