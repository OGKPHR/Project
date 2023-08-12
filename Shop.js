// JavaScript code for the cart system
const cartItems = JSON.parse(localStorage.getItem('cart')) || [];
const cartTotal = document.getElementById('cart-total');
const cartItemsList = document.getElementById('cart-items');
const clearCartBtn = document.getElementById('clear-cart-btn');

// Function to check if a product is already in the cart
function isProductInCart(productName) {
    return cartItems.some(item => item.productName === productName);
}

// Function to add an item to the cart
function addToCart(productName, price) {
    if (!isProductInCart(productName)) {
        cartItems.push({ productName, price });
        updateCart();
    }
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
        deleteBtn.textContent = 'ลบ'; //(ลบ)
        deleteBtn.addEventListener('click', () => {
            removeFromCart(index);
        });

        listItem.appendChild(deleteBtn);
        cartItemsList.appendChild(listItem);

        total += item.price;
    });

    cartTotal.textContent = `฿${total.toFixed(2)}`;
    saveCartToLocalStorage();
    updateAddToCartButtons();

    // Update "Add to Cart" buttons based on the cart content
    updateAddToCartButtons();
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
function updateAddToCartButtons() {
    const addToCartButtons = document.querySelectorAll('.product button');
    addToCartButtons.forEach(button => {
        const productDiv = button.closest('.product');
        const productName = productDiv.querySelector('h2').textContent;
        button.disabled = isProductInCart(productName);
        button.textContent = isProductInCart(productName) ? 'อยู่ในตะกร้า' : 'ใส่ในตะกร้า'; // (ใส่ในตะกร้า)
    });
}

// Event listener for the "Clear Cart" button
clearCartBtn.addEventListener('click', () => {
    clearCart();
});

// Function to save the cart to Local Storage
function saveCartToLocalStorage() {
    localStorage.setItem('cart', JSON.stringify(cartItems));
}

// Initial update of the cart content
updateCart();
