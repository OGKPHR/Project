<?php
if (!isset($_SESSION['userid'])) {
    header("Location: Shop.php");
    exit();
}

require_once "connection.php";

$user_id = $_SESSION['userid'];


// Function to display user information and phone numbers
$userQuery = "SELECT * FROM user WHERE id = $user_id";
$userResult = mysqli_query($conn, $userQuery);

if (!$userResult) {
    die("Database error: " . mysqli_error($conn));
}

$userData = mysqli_fetch_assoc($userResult);

if (!$userData) {
    die("User not found.");
}

?>

<style>
    /* Basic CSS styles for the navigation menu */
    ul.nav-menu {
        list-style-type: none;
        margin: 0;
        padding: 0;
        background-color: #1111;
        overflow: hidden;
        height: auto; /* Change the fixed height to 'auto' for responsiveness */
    }

    ul.nav-menu li {
        display: inline-block;
        margin: 0 10px; /* Add margin to space out menu items */
        position: relative; /* Add a position property */
        z-index: 999; /* Set a higher z-index value */
    }

    ul.nav-menu li a {
        display: block;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-weight: bolder;
        color: burlywood !important; /* Text color with !important */
        transition: border-radius 0.3s, background-color 0.3s, color 0.3s; /* Smooth transition */
    }

    ul.nav-menu li a:hover {
        background-color: #333; /* Background color on hover */
        border-radius: 5px; /* Add border radius on hover */
        color: white !important; /* Text color on hover with !important */
    }

    /* CSS to position the cart icon and user info to the right */
    .cart-icon {
        float: right;
    }

    .user-info {
        float: right;
        margin-top: 0px; /* Adjust the vertical alignment */
    }

    /* Media query for screens with a maximum width of 768px (e.g., tablets and phones) */
    @media (max-width: 768px) {
        ul.nav-menu {
            text-align: center; /* Center-align the menu items */
        }

        ul.nav-menu li {
            display: block;
            margin: 10px 0; /* Add margin to space out menu items vertically */
        }

        ul.nav-menu li a {
            padding: 10px 0; /* Reduce the padding for smaller screens */
        }
    }

    .modal {
        z-index: 9999;
    }

    .dropdown-menu-custom {
        z-index: 9999; /* Set a high z-index value */
    }

    ul.nav-menu li {
        display: inline-block;
        margin: 0 10px;
    }

    #navbar {
        overflow: hidden;
        background-color: rgb(255, 255, 255);
        display: flex;
        transition: height 0.3s; /* Add transition for smooth animation */
    }

    #navbar.minimized {
        height: 50px; /* Set the minimized height */
    }

    #navbar a {
        float: left;
        display: block;
        color: rgb(0, 0, 0);
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 17px;
    }

    #navbar a:hover {
        background-color: #ffffff;
        color: black;
    }

    #navbar a.active {
        background-color: #04AA6D;
        color: red;
    }
    /* CSS for the cart count */
.cart-item-count {
    background-color: red;
    color: white;
    border-radius: 50%;
    padding: 3px 6px;
    font-size: 12px;
    position: relative;
    top: -10px;
    right: 5px;
}
</style>
<?php
if (!isset($_SESSION['userid'])) {
    header("Location: Shop.php");
    exit();
}

require_once "connection.php";

$user_id = $_SESSION['userid'];
$cartQuery = "SELECT * FROM cart WHERE user_id = $user_id";
$cartResult = mysqli_query($conn, $cartQuery);

$cartItems = mysqli_fetch_all($cartResult, MYSQLI_ASSOC);


// Calculate the total price
$totalPrice = 0;
foreach ($cartItems as $item) {
    $totalPrice += $item['product_price'];
}
?>


<!-- Your HTML code remains the same -->

<div id="navbar" style="background-color: #111; height: 160px;">
    <img src="ICON\LOGO.png" href="Shop.php" style="padding: 40px;height: inherit;">
    <div class="user-info" style="top: 0px;">
        <ul class="nav-menu"style=" height: 160px;">
            <li><a href="Shop.php">Home</a></li>
            <li><a href="predict.php">Predict</a></li>
            <li><a href="#contact">Contact</a></li>
            <li class="nav-item">
            <a class="nav-link" href="cart_items.php" >
        <i class="fas fa-shopping-cart"></i> My Cart
        <span id="cart-count" class="cart-item-count">0</span>
    </a>
</li>
<script>
    // Add an event listener to the cart icon button
    document.getElementById('cart-icon-button').addEventListener('click', function() {
        $('#cartModal').modal('show'); // Trigger the modal when the button is clicked
    });

    function updateCartCount() {
    $.ajax({
        url: 'get_cart_count.php', // Replace with the actual URL to fetch cart count
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                // Update the cart count in the HTML
                $('#cart-count').text(response.cartCount);
            }
        },
        error: function () {
            // Handle errors here
        }
    });
}

// Call the function to update cart count on page load
updateCartCount();

</script>



            <li class="nav-item dropdown no-arrow" style="align-content: right;padding-left: 100px;">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small" style="color: white;">
                        <?php echo $_SESSION['fname']; ?>
                    </span>
                    <img style="align-content: center; width: 50px; height: 50px;" class="img-profile rounded-circle"
                        src="img/undraw_profile.svg">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in dropdown-menu-custom"
                    aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </div>
</div>
</div>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>




  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="color: black; ">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" >Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <script src="aaw.js"></script>
        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>

        <!-- Page level plugins -->
        <script src="vendor/chart.js/Chart.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="js/demo/chart-area-demo.js"></script>
        <script src="js/demo/chart-pie-demo.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
