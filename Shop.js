// JavaScript code for the cart system
const cartItems = [];
const cartTotal = document.getElementById('cart-total');
const cartItemsList = document.getElementById('cart-items');
const clearCartBtn = document.getElementById('clear-cart-btn');

// Function to check if a product is already in the cart
function isProductInCart(productName) {
    return cartItems.some(item => item.productName === productName);
}

// Function to update the cart content and total
function updateCart() {
    cartItemsList.innerHTML = '';
    let total = 0;

    cartItems.forEach((item, index) => {
        const listItem = document.createElement('li');
        listItem.textContent = `${item.productName} - ฿${item.price.toFixed(2)}`;

        // Add a delete button for each cart item
        const deleteBtn = document.createElement('button');
        deleteBtn.textContent = 'ลบ'; // You can customize the label
        deleteBtn.addEventListener('click', () => {
            removeFromCart(index);
        });

        listItem.appendChild(deleteBtn);
        cartItemsList.appendChild(listItem);

        total += item.price;
    });

    cartTotal.textContent = `฿${total.toFixed(2)}`;
}

// Function to add an item to the cart
function addToCart(productName, price) {
    if (!isProductInCart(productName)) {
        cartItems.push({ productName, price });
        updateCart();
    }
}

// Function to remove an item from the cart
function removeFromCart(index) {
    cartItems.splice(index, 1);
    updateCart();
}

// Function to clear the cart
function clearCart() {
    cartItems.length = 0;
    updateCart();
}

// Event listener for the "Add to Cart" buttons
const addToCartButton = document.querySelectorAll('.product button');
        addToCartButton.forEach(button => {
    button.addEventListener('click', () => {
        const productDiv = button.closest('.product');
        const productName = productDiv.querySelector('h2').textContent;
        const price = parseFloat(productDiv.querySelector('.price').textContent.slice(1));
        addToCart(productName, price);
    });
});

// Event listener for the "Clear Cart" button
clearCartBtn.addEventListener('click', () => {
    clearCart();
});



// When the user scrolls the page, execute myFunction
window.onscroll = function() {myFunction()};

// Get the navbar
var navbar = document.getElementById("navbar");

// Get the offset position of the navbar
var sticky = navbar.offsetTop;

// Add the sticky class to the navbar when you reach its scroll position. Remove "sticky" when you leave the scroll position
function myFunction() {
  if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky")
  } else {
    navbar.classList.remove("sticky");
  }
}