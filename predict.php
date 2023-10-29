<?php session_start(); ?>
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

<style>
    .btn-gradient-border {
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

    .input-group {
        width: 100%;
        max-width: 400px; /* Adjust the maximum width as needed */
        margin: 0 auto; /* Center the input box and button */
    }

    /* Input Box Width Adjustment */
    .form-control {
        width: 100%;
        padding: 15px; /* Adjust the padding as needed */
        font-size: 18px; /* Adjust the font size as needed */
        border-radius: 5px; /* Add some border-radius for a better appearance */
    }

    .custom-textbox {
        width: 100%; /* Set the width to 100% to fill the available space */
        max-width: 400px; /* Adjust the maximum width as needed */
        margin: 0 auto; /* Center the input box */
    }

    .form-control {
        height: 60px; /* Adjust the height as needed */
        padding: 15px; /* Adjust the padding as needed */
        font-size: 24px; /* Adjust the font size as needed */
    }

    .custom-button {
        text-align: center;
    }

    .btn-lg {
        
        padding: 15px 30px; /* Adjust padding for larger button size */
        font-size: 24px; /* Adjust font size for larger button */
    }


    /* Main Content Styles */
    main {
      
        flex: 1; /* Allow the main content to expand and fill available space */
        margin: 0 auto;
        max-width: 800px;
        text-align: center;
        background-color: rgba(0, 0, 0, 0.5);
        padding: 20px;
        border-radius: 10px;
        height: 100vh;
   
    }

    /* Footer Styles */
    footer {
        text-align: center;
        padding: 20px;
        position: absolute;
        bottom: 0;
        width: 100%;
    }
</style>

<body style="padding-top: 0px;background-color: #111;color: white;">

    <?php include('newnav.php'); ?>

    <main style="background-image: url('/ICON/predict.png'); background-size: cover; background-repeat: no-repeat; background-attachment: fixed; padding-top: 500px;">
    <div id="warningMessage" style="border-radius: 10px; background-color: pink; color: red;"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="custom-textbox">
                    <input type="text" class="form-control" name="number" id="phoneNumberInput"
                        placeholder="กรอกเบอร์" oninput="limitToTenDigits(this)">
                </div>
                <div class="custom-button">
                    <button class="btn btn-gradient-border btn-glow btn-lg"
                        onclick="analyzePhoneNumber()">วิเคราะห์เบอร์</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function limitToTenDigits(input) {
            // Remove any non-digit characters
            input.value = input.value.replace(/\D/g, '');

            // Limit to 10 digits
            if (input.value.length > 10) {
                input.value = input.value.slice(0, 10);
            }
        }

        function analyzePhoneNumber() {
            // Get the phone number entered by the user
            var phoneNumber = document.getElementById('phoneNumberInput').value;

            // Check if the phone number is empty
            if (phoneNumber === "") {
                // Display a warning message
                document.getElementById('warningMessage').textContent = 'Please enter a phone number.';
                return; // Exit the function
            } else if (phoneNumber.length !== 10) {
                // Display a warning message for incorrect length
                document.getElementById('warningMessage').textContent = 'Please enter 10 numeric digits.';
                return; // Exit the function
            } else {
                // Clear any previous warning message
                document.getElementById('warningMessage').textContent = '';
            }

            // Create a URL for the new tab, passing the phone number as a query parameter
            var analyzeURL = 'analyze.php?phone_number=' + encodeURIComponent(phoneNumber);

            // Open a new tab with the analysis result
            window.open(analyzeURL, '_blank');
        }
    </script>
</main>


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
<script src="cartcount.js" >
   
</script>
