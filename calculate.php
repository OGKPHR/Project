<?php
function calculateAverageScoreAndPrice($productData, $conn) {
    // Extract phone number and remove the first 3 digits
    $phone_number = $productData;
    $phoneNumberWithoutFirstThreeDigits = substr($phone_number, 3);

    // Split the remaining phone number into pairs
    $pairs = [];
    for ($i = 0; $i < strlen($phoneNumberWithoutFirstThreeDigits) - 1; $i++) {
        $pair = substr($phoneNumberWithoutFirstThreeDigits, $i, 2);
        $pairs[] = $pair;
    }

    // Initialize an array to store the total scores for each category
    $categoryTotals = [
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
    ];

    $totalPairs = count($pairs);

    foreach ($pairs as $pair) {
        $pairMeaningQuery = "SELECT Pair, Meaning, finance, work, fortune, love, health, utterance, mind, charm, personality, learning FROM numbermeanings WHERE Pair = '$pair'";
        $pairMeaningResult = mysqli_query($conn, $pairMeaningQuery);
        $pairMeaning = mysqli_fetch_assoc($pairMeaningResult);

        if ($pairMeaning) {
            // Update the total scores for each category
            foreach ($categoryTotals as $category => $score) {
                $categoryTotals[$category] += $pairMeaning[$category] / $totalPairs;
            }
        }
    }

    if ($totalPairs > 0) {
        // Calculate the average score based on the total scores
        $totalScores = array_sum($categoryTotals) / 10;

        // Calculate the grade based on the average score
        $grade = calculateGrade($totalScores);

        // Calculate the price based on the average score
        $price = $totalScores * 250;
    } else {
        $totalScores = 0;
        $grade = 'N/A';
        $price = 0;
    }

    // Return the calculated average score, grade, and price as an associative array
    return [
        'averageScore' => $totalScores,
        'grade' => $grade,
        'price' => $price
    ];
}

function calculateGrade($totalScores) {
    if ($totalScores>= 80.0) {
        return 'A';
    } elseif ($totalScores>= 75.0) {
        return 'B+';
    } elseif ($totalScores>= 70.0) {
        return 'B';
    } elseif ($totalScores>= 65.0) {
        return 'C+';
    } elseif ($totalScores>= 60.0) {
        return 'C';
    } elseif ($totalScores>= 55.0) {
        return 'D+';
    } elseif ($totalScores>= 50.0) {
        return 'D';
    } else {
        return 'F';
    }
}


?>
