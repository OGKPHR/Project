<?php
session_start();

if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit();
} else {
    require_once "connection.php";
    
    $providerOptionsQuery = "SELECT * FROM providers";
    $providerOptionsResult = mysqli_query($conn, $providerOptionsQuery);
    $providerOptions = mysqli_fetch_all($providerOptionsResult, MYSQLI_ASSOC);
}

// Function to calculate the grade based on a score
function calculateGrade($score)
{
    if ($score >= 80 && $score <= 100) {
        return 'A';
    } elseif ($score >= 75 && $score <= 79) {
        return 'B+';
    } elseif ($score >= 70 && $score <= 74) {
        return 'B';
    } elseif ($score >= 65 && $score <= 69) {
        return 'C+';
    } elseif ($score >= 60 && $score <= 64) {
        return 'C';
    } elseif ($score >= 55 && $score <= 59) {
        return 'D+';
    } elseif ($score >= 50 && $score <= 54) {
        return 'D';
    } else {
        return 'F';
    }
}
include('search.php');
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



</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<body style="padding-top: 0px;background-color: #111;color: white;">

<?php
        
        include('newnav.php');
        ?>
    <div class="content" style="padding-top: 140px;">
        <?php
        include('connection.php');
        $query = "SELECT * FROM uploadfile" or die("Error: " . mysqli_error());
        $resultbanner = mysqli_query($conn, $query);
        ?>
        <div class="banner">
            <div id="carouselExample" class="carousel slide" data-ride="carousel" style="margin-top">
                <div class="carousel-inner">
                    <?php
                    $resultbanner = mysqli_query($conn, $query);
                    $active = true; // Set the first slide as active
                    while ($row = mysqli_fetch_array($resultbanner)) {
                        echo "<div class='carousel-item " . ($active ? " active" : "") . "'>";
                        echo "<div class='carousel-img-container'>";
                        echo "<img style='max-height: 500px; width: 100%; object-fit: contain;' src='fileupload/" . $row['fileupload'] . "' class='d-block w-100'>";
                        echo "</div>";
                        echo "</div>";
                        $active = false; // Disable active for subsequent slides
                    }
                    ?>
                </div>
                <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>

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

        .container1 {
            padding-top: 10px;
            padding left: 100px;
            margin: auto;
            width: 100%;
            background-color: #a47f00;
            border-radius: 10px;
            padding-left: 70px;
            padding-right: 70px;
        }

        /* Style the links inside the text div */
        .text a {
            color: white;
            /* Set the link color to white */
            text-decoration: none;
            /* Add an underline to the links */
        }

        /* Remove the default link color styles */
        .text a:hover,
        .text a:visited {
            color: white;
            /* Keep the link color white on hover and visited states */
            text-decoration: none;
            /* Keep the underline on hover and visited states */
        }
        .btn.disabled {
        background-color: black;
        color: white;
        pointer-events: none; /* To disable clicking on the button */
    }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Auto slide the carousel every 2 seconds
        $('#carouselExample').carousel({
            interval:3500
        });
    </script>
    <div class="container1">
        <div class="process-number mt-4">
            <div class="row">
                <!-- Add a div to display the warning message -->
                <div id="warningMessage" style=" border-radius: 10px; background-color: pink; color: red;"></div>
                <div class="col-md-6 mb-3">
                    <div class="input-group">
                        <input type="text" class="form-control" name="number" id="phoneNumberInput"
                            placeholder="กรอกเบอร์" oninput="limitToTenDigits(this)">
                        <div class="input-group-append">
                            <button class="btn btn-gradient-border btn-glow"
                                onclick="analyzePhoneNumber()">วิเคราะห์เบอร์</button>
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



                <div class="col-md-6 mb-3">
                    <input type="text" class="form-control" name="searchQuery" placeholder="ค้นหาสินค้า">
                </div>

            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>ประเภท</label>
                    <select class="custom-select" id="TYPES" name="TYPES" required>
                        <?php foreach ($typeOptions as $typeOption): ?>
                            <option value="<?php echo $typeOption['option_value']; ?>">
                                <?php echo $typeOption['option_value']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label>จำนวน</label>
                    <select class="custom-select" id="sumnumber">
                        <option value="50">50</option>
                        <option value="60">60</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label>เครือข่าย</label>
                    <select class="custom-select" id="Provider" name="Provider" required>
                        <?php foreach ($providerOptions as $providerOption): ?>
                            <option value="<?php echo $providerOption['option_value']; ?>">
                                <?php echo $providerOption['option_value']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>เรียงลำดับ</label>
                    <select class="custom-select" id="sortnumber" name="sortnumber">
                        <option value="maxtomin">ราคาเรียงจากน้อยไปมาก</option>
                        <option value="mintomax">ราคาเรียงจากมากไปน้อย</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label>ช่วงราคา</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="price1" placeholder="ต่ำสุด">
                        <input type="text" class="form-control" name="price2" placeholder="สูงสุด">
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label>ชุดเลขที่ชอบ</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="set1" placeholder="เลขต่ำสุด">
                        <input type="text" class="form-control" name="set2" placeholder="เลขสูงสุด">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3 button-num">
                    <label>ตัวเลขที่ชอบ</label>
                    <?php for ($i = 0; $i < 10; $i++): ?>
                        <button class="btn btn-light" data-favnum="<?php echo $i; ?>">
                            <?php echo $i; ?>
                        </button>
                    <?php endfor; ?>
                </div>
                <div class="col-md-6 mb-3 button-num">
                    <label>ตัวเลขที่ไม่ชอบ</label>
                    <?php for ($i = 0; $i < 10; $i++): ?>
                        <button class="btn btn-light" data-favnum="<?php echo $i; ?>">
                            <?php echo $i; ?>
                        </button>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>


    <main>
        <div class="border-silver box-snum">
        <section class="product-listings">
    <?php
       $query = "SELECT p.* FROM product p
       LEFT JOIN order_item oi ON p.id = oi.product_id 
       WHERE oi.product_id IS NULL";
           $result = mysqli_query($conn, $query);
       
    // Retrieve the list of products in the user's cart
    $user_id = $_SESSION['userid'];
    $cartQuery = "SELECT product_id FROM cart_item WHERE user_id = $user_id";
    $cartResult = mysqli_query($conn, $cartQuery);
    $cartItems = [];
    while ($row = mysqli_fetch_assoc($cartResult)) {
        $cartItems[] = $row['product_id'];
    }

    while ($row = mysqli_fetch_assoc($result)):
        $avgScore = $row['avg_score'];
        $gradeText = calculateGrade($avgScore);

        $providerId = isset($row['provider_id']) ? $row['provider_id'] : null;
        if ($providerId !== null) {
            $providerQuery = "SELECT option_value, providerlogo FROM providers WHERE id = $providerId";
            $providerResult = mysqli_query($conn, $providerQuery);
            $providerRow = mysqli_fetch_assoc($providerResult);
            $providerName = $providerRow['option_value'];
            $providerImage = $providerRow['providerlogo'];
        }

        $productId = $row['id'];
        $productName = $row['phonenumber'];
        $productPrice = $row['Price'];
        $provider_Id = $row['provider_id'];
        // Check if the product is in the cart
        $isInCart = in_array($productId, $cartItems);
        
        ?>
        <div class="product" style="width: 300px; max-height: fit-content; border-radius: 20px;">
            <?php if (isset($providerImage)): ?>
                <img style="width: 80px;" src="provider/<?php echo $providerImage; ?>"
                    alt="<?php echo $providerName; ?>">
                <h3 style="position: absolute; top: 1; right: 4; font-weight: bolder; font-style: italic; color: greenyellow; 
                        text-align: center; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                    <?php echo $gradeText; ?>
                </h3>
            <?php endif; ?>

            <div class="text"
                style="text-align: center; font-family: Arial, Helvetica, Arial, Helvetica, sans-serif;">
                <h2><b><a href="product_info.php?product_id=<?php echo $row['id']; ?>" target="_blank">
                            <?php echo $row['phonenumber']; ?>
                        </a></b></h2>
            </div>
            <div class="text"
                style="text-align: right; font-family: Arial, Helvetica, Arial, Helvetica, sans-serif;">
                <h6><b>ราคา:
                        <?php echo number_format($row['Price']); ?> ฿
                    </b></h6>
            </div>
              <!-- Add to Cart Button with AJAX -->
        <?php
        $isInCart = in_array($row['id'], $cartItems);
        $buttonClass = $isInCart ? "btn btn-gradient-border disabled" : "btn btn-gradient-border btn-glow add-to-cart-button";
        $buttonText = $isInCart ? "Added to Cart" : "Add to Cart";
        ?>

<button class="<?php echo $buttonClass; ?>"
            data-product-id="<?php echo $row['id']; ?>"
            data-product-name="<?php echo $row['phonenumber']; ?>"
            data-product-price="<?php echo $row['Price']; ?>"
            data-provider-id="<?php echo $row['provider_id']; ?>"
            data-in-cart="<?php echo $isInCart; ?>">
            <?php echo $buttonText; ?>
        </button>
        </div>
    <?php endwhile; ?>
</section>

        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" style="color: black; z-index: 9999;">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <footer>
        <p>&copy; 2023 Lucky Phone Number Shop. All rights reserved.</p>
    </footer><script>
    $(document).ready(function () {
        $(".add-to-cart-button").click(function () {
            var button = $(this);
            if (!button.hasClass("disabled")) {
                button.addClass("disabled").text("Added to Cart");
                // Make an AJAX request to add the product to the cart
                var productID = button.data("product-id");
                var productName = button.data("product-name");
                var productPrice = button.data("product-price");
                var providerID = button.data("provider-id");
                $.ajax({
                    type: "POST",
                    url: "add_to_cart.php",
                    data: {
                        product_id: productID,
                        product_name: productName,
                        product_price: productPrice,
                        provider_id: providerID
                    },
                    success: function (response) {
                        // Handle the success response, if needed
                    },
                    error: function () {
                        // Handle any errors that occur during the AJAX request
                    }
                });
            }
        });
    });
</script>
<script src="cartcount.js"></script>
<script>
    $(document).ready(function () {
        $(".add-to-cart-button").click(function () {
            var button = $(this);
            if (!button.hasClass("disabled")) {
                button.addClass("disabled").text("Added to Cart");
                // Make an AJAX request to add the product to the cart
                var productID = button.data("product-id");
                $.ajax({
                    type: "POST",
                    url: "add_to_cart.php",
                    data: {
                        product_id: productID
                    },
                    success: function (response) {
                        // Handle the success response, if needed

                        // After successfully adding to the cart, call the cart count update function
                        updateCartCount();
                    },
                    error: function (xhr, status, error) {
                        // Handle errors that occur during the AJAX request
                        console.log("AJAX error: " + error);
                        // You can display an error message or take other actions here
                        alert("An error occurred while adding to the cart.");
                    }
                });
            }
        });
    });
</script>

   
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

</html><!-- Include Bootstrap CSS -->
