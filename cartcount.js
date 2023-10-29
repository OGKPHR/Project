
    $(document).ready(function() {
        function updateCartCount() {
            $.ajax({
                url: 'get_cart_count.php', // PHP script to get cart count
                method: 'GET',
                success: function(data) {
                    $('#cart-count').text(data);
                }
            });
        }

        // Call the function on page load $product_price = $_POST["product_price"];
        updateCartCount();
    });
