// Cart functionality for CalsFine Order Page
let cart = [];
let isCartOpen = false;

// Cart Management Functions
export function addToCart(id, name, price, stock) {
    const existingItem = cart.find((item) => item.id === id);

    if (existingItem) {
        if (existingItem.quantity < stock) {
            existingItem.quantity += 1;
        } else {
            alert("Stok tidak mencukupi!");
            return;
        }
    } else {
        cart.push({
            id: id,
            name: name,
            price: price,
            quantity: 1,
            stock: stock,
        });
    }

    updateCartDisplay();
    showCartElements();

    // Auto-open cart when first item is added
    if (cart.length === 1 && !isCartOpen) {
        toggleCart();
    }
}

export function updateQuantity(id, change) {
    const item = cart.find((item) => item.id === id);
    if (!item) return;

    const newQuantity = item.quantity + change;

    if (newQuantity <= 0) {
        removeFromCart(id);
    } else if (newQuantity <= item.stock) {
        item.quantity = newQuantity;
        updateCartDisplay();
    } else {
        alert("Stok tidak mencukupi!");
    }
}

export function removeFromCart(id) {
    cart = cart.filter((item) => item.id !== id);
    updateCartDisplay();

    // Close cart if empty and open
    if (cart.length === 0 && isCartOpen) {
        toggleCart();
    }
}

export function toggleCart() {
    const cartSidebar = document.getElementById("cart-sidebar");
    const cartOverlay = document.getElementById("cart-overlay");

    // Pastikan sidebar terlihat terlebih dahulu
    cartSidebar.classList.remove("hidden");

    if (isCartOpen) {
        cartSidebar.classList.add("translate-x-full");
        cartOverlay.classList.add("hidden");
        isCartOpen = false;
    } else {
        cartSidebar.classList.remove("translate-x-full");
        cartOverlay.classList.remove("hidden");
        isCartOpen = true;
    }
}

function showCartElements() {
    const cartSidebar = document.getElementById("cart-sidebar");
    cartSidebar.classList.remove("hidden");
}

function hideCartElements() {
    const cartSidebar = document.getElementById("cart-sidebar");
    const cartOverlay = document.getElementById("cart-overlay");

    cartOverlay.classList.add("hidden");
    isCartOpen = false;
}

function updateCartDisplay() {
    const cartItems = document.getElementById("cart-items");
    const cartCount = document.getElementById("cart-count");
    const cartToggleCount = document.getElementById("cart-toggle-count");
    const totalItems = document.getElementById("total-items");
    const checkoutTotal = document.getElementById("checkout-total");

    // Update cart counts
    const totalQty = cart.reduce((sum, item) => sum + item.quantity, 0);
    const total = cart.reduce(
        (sum, item) => sum + item.price * item.quantity,
        0
    );

    cartCount.textContent = totalQty;
    cartToggleCount.textContent = totalQty;
    totalItems.textContent = totalQty;
    checkoutTotal.textContent = total.toLocaleString("id-ID");

    if (cart.length === 0) {
        // Show empty cart message and clear items
        cartItems.innerHTML = `
            <div id="empty-cart" class="text-center py-8 text-gray-500">
                <img src="/images/shopping-basket.svg" alt="Empty Cart" class="w-16 h-16 mx-auto mb-4 opacity-30">
                <p>Keranjang masih kosong</p>
                <p class="text-sm">Tambahkan menu untuk mulai berbelanja</p>
            </div>
        `;
        return;
    }

    // Update cart items - replace entire content
    cartItems.innerHTML = cart
        .map(
            (item) => `
        <div class="flex items-start justify-between p-3 border border-gray-200 rounded">
            <div class="flex-1 min-w-0">
                <h4 class="text-sm font-medium truncate">${item.name}</h4>
                <p class="text-xs text-gray-600">Rp ${item.price.toLocaleString(
                    "id-ID"
                )}</p>
            </div>
            <div class="flex flex-col items-end space-y-2 ml-2">
                <div class="flex items-center space-x-1">
                    <button 
                        onclick="updateQuantity(${item.id}, -1)"
                        class="w-6 h-6 bg-gray-200 rounded text-xs hover:bg-gray-300 transition flex items-center justify-center">
                        -
                    </button>
                    <span class="text-sm font-medium w-8 text-center">${
                        item.quantity
                    }</span>
                    <button 
                        onclick="updateQuantity(${item.id}, 1)"
                        class="w-6 h-6 bg-primary-500 text-white rounded text-xs hover:bg-primary-600 transition flex items-center justify-center">
                        +
                    </button>
                </div>
                <button 
                    onclick="removeFromCart(${item.id})"
                    class="text-red-500 hover:text-red-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    `
        )
        .join("");
}

export function checkout() {
    if (cart.length === 0) {
        alert("Keranjang masih kosong!");
        return;
    }

    const total = cart.reduce(
        (sum, item) => sum + item.price * item.quantity,
        0
    );
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);

    alert(
        `Checkout berhasil!\n\nTotal Item: ${totalItems}\nTotal Harga: Rp ${total.toLocaleString(
            "id-ID"
        )}\n\nTerima kasih telah berbelanja di CalsFine!`
    );

    // Reset cart
    cart = [];
    updateCartDisplay();

    // Close cart after checkout
    if (isCartOpen) {
        toggleCart();
    }
}

// Initialize cart when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    // Make functions available globally for onclick handlers
    window.addToCart = addToCart;
    window.updateQuantity = updateQuantity;
    window.removeFromCart = removeFromCart;
    window.toggleCart = toggleCart;
    window.checkout = checkout;

    // Initialize cart display
    updateCartDisplay();

    // Event listener for cart toggle button
    document
        .getElementById("cart-toggle")
        .addEventListener("click", toggleCart);
});
