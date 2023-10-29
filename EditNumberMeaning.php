<?php
session_start();
require_once "connection.php";

// Check if the ID parameter is provided in the URL
if (isset($_GET['id'])) {
    $numberMeaningId = $_GET['id'];

    // Fetch the data for the specified ID
    $query = "SELECT * FROM numbermeanings WHERE ID = $numberMeaningId";
    $result = mysqli_query($conn, $query);
    $numberMeaning = mysqli_fetch_assoc($result);

    if (!$numberMeaning) {
        // Handle the case where the ID doesn't exist
        echo "Number Meaning not found.";
        exit;
    }
} else {
    // Handle the case where the ID parameter is missing
    echo "ID parameter is missing.";
    exit;
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the edited data from the form and sanitize
    $editedPair = mysqli_real_escape_string($conn, $_POST['editedPair']);
    $editedMeaning = mysqli_real_escape_string($conn, $_POST['editedMeaning']);
    $editedFinance = mysqli_real_escape_string($conn, $_POST['editedFinance']);
    $editedWork = mysqli_real_escape_string($conn, $_POST['editedWork']);
    $editedFortune = mysqli_real_escape_string($conn, $_POST['editedFortune']);
    $editedLove = mysqli_real_escape_string($conn, $_POST['editedLove']);
    $editedHealth = mysqli_real_escape_string($conn, $_POST['editedHealth']);
    $editedutterance = mysqli_real_escape_string($conn, $_POST['editedutterance']);
    $editedmind = mysqli_real_escape_string($conn, $_POST['editedmind']);
    $editedcharm = mysqli_real_escape_string($conn, $_POST['editedcharm']);
    $editedpersonality = mysqli_real_escape_string($conn, $_POST['editedpersonality']);
    $editedlearning = mysqli_real_escape_string($conn, $_POST['editedlearning']);
    
    // Update the data in the database
    $updateQuery = "UPDATE numbermeanings SET Pair = '$editedPair', Meaning = '$editedMeaning', 
                    finance = '$editedFinance', work = '$editedWork', fortune = '$editedFortune', 
                    love = '$editedLove', health = '$editedHealth', utterance = '$editedutterance', 
                    mind = '$editedmind', charm = '$editedcharm', personality = '$editedpersonality', 
                    learning = '$editedlearning'
                    WHERE ID = $numberMeaningId";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        // Redirect back to NumberMeaningsManage.php after successful update
        header("Location: NumberMeaningsManage.php");
        exit;
    } else {
        echo "Update failed: " . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Number Meaning</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Edit Number Meaning</h1>
                    <form method="post">
                        <input type="hidden" name="numberMeaningId" value="<?php echo $numberMeaning['ID']; ?>">
                        <div class="form-group">
                            <label for="editedPair">Pair:</label>
                            <input type="text" name="editedPair" id="editedPair" value="<?php echo $numberMeaning['Pair']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="editedMeaning">Meaning:</label>
                            <textarea name="editedMeaning" id="editedMeaning" rows="6"><?php echo $numberMeaning['Meaning']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="editedFinance">Finance:</label>
                            <input type="text" name="editedFinance" id="editedFinance" value="<?php echo $numberMeaning['finance']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="editedWork">Work:</label>
                            <input type="text" name="editedWork" id="editedWork" value="<?php echo $numberMeaning['work']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="editedFortune">Fortune:</label>
                            <input type="text" name="editedFortune" id="editedFortune" value="<?php echo $numberMeaning['fortune']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="editedLove">Love:</label>
                            <input type="text" name="editedLove" id="editedLove" value="<?php echo $numberMeaning['love']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="editedHealth">Health:</label>
                            <input type="text" name="editedHealth" id="editedHealth" value="<?php echo $numberMeaning['health']; ?>">
                        </div>
                        <div class="form-group"> 	
                            <label for="editedutterance">Utterance:</label>
                            <input type="text" name="editedutterance" id="editedutterance" value="<?php echo $numberMeaning['utterance']; ?>">
                        </div>
                        <div class="form-group"> 	
                            <label for="editedmind">Mind:</label>
                            <input type="text" name="editedmind" id="editedmind" value="<?php echo $numberMeaning['mind']; ?>">
                        </div>
                        <div class="form-group"> 	
                            <label for="editedcharm">Charm:</label>
                            <input type="text" name="editedcharm" id="editedcharm" value="<?php echo $numberMeaning['charm']; ?>">
                        </div>
                        <div class="form-group"> 	 	
                            <label for="editedpersonality">Personality:</label>
                            <input type="text" name="editedpersonality" id="editedpersonality" value="<?php echo $numberMeaning['personality']; ?>">
                        </div>
                        <div class="form-group"> 	
                            <label for="editedlearning">Learning:</label>
                            <input type="text" name="editedlearning" id="editedlearning" value="<?php echo $numberMeaning['learning']; ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>
