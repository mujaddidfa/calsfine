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

    // Show checkout modal
    showCheckoutModal();
}

// Initialize cart when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    // Make functions available globally for onclick handlers
    window.addToCart = addToCart;
    window.updateQuantity = updateQuantity;
    window.removeFromCart = removeFromCart;
    window.toggleCart = toggleCart;
    window.checkout = checkout;
    window.showCheckoutModal = showCheckoutModal;
    window.closeCheckoutModal = closeCheckoutModal;
    window.showOrderPreview = showOrderPreview;
    window.closeOrderPreview = closeOrderPreview;
    window.backToCheckoutForm = backToCheckoutForm;
    window.confirmOrder = confirmOrder;
    window.submitOrder = submitOrder;

    // Initialize cart display
    updateCartDisplay();

    // Event listener for cart toggle button
    document
        .getElementById("cart-toggle")
        .addEventListener("click", toggleCart);

    // Set automatic pickup date to tomorrow
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const pickupDateInput = document.getElementById("pickup-date");
    if (pickupDateInput) {
        // Set the hidden input value to tomorrow
        pickupDateInput.value = tomorrow.toISOString().split("T")[0];

        // Update the display text with formatted date
        const pickupDateDisplay = document.getElementById(
            "pickup-date-display"
        );
        if (pickupDateDisplay) {
            const options = {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
            };
            const formattedDate = tomorrow.toLocaleDateString("id-ID", options);
            pickupDateDisplay.textContent = formattedDate;
        }
    }

    // Load locations for dropdown
    loadLocations();
});

// Checkout Modal Functions
function showCheckoutModal() {
    const modal = document.getElementById("checkout-modal");
    const checkoutItems = document.getElementById("checkout-items");
    const checkoutTotal = document.getElementById("checkout-modal-total");

    // Show modal with flex display
    modal.classList.remove("hidden");
    modal.classList.add("flex");

    // Setup pickup date to tomorrow
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const pickupDateInput = document.getElementById("pickup-date");
    if (pickupDateInput) {
        // Set the hidden input value to tomorrow
        pickupDateInput.value = tomorrow.toISOString().split("T")[0];

        // Update the display text with formatted date
        const pickupDateDisplay = document.getElementById(
            "pickup-date-display"
        );
        if (pickupDateDisplay) {
            const options = {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
            };
            const formattedDate = tomorrow.toLocaleDateString("id-ID", options);
            pickupDateDisplay.textContent = formattedDate;
        }
    }

    // Populate order summary
    const total = cart.reduce(
        (sum, item) => sum + item.price * item.quantity,
        0
    );
    checkoutTotal.textContent = total.toLocaleString("id-ID");

    // Populate items
    checkoutItems.innerHTML = cart
        .map(
            (item) => `
        <div class="flex justify-between items-center text-sm">
            <span>${item.name} x${item.quantity}</span>
            <span>Rp ${(item.price * item.quantity).toLocaleString(
                "id-ID"
            )}</span>
        </div>
    `
        )
        .join("");
}

function closeCheckoutModal() {
    const modal = document.getElementById("checkout-modal");
    modal.classList.add("hidden");
    modal.classList.remove("flex");
}

// Order Preview Functions
function showOrderPreview() {
    const form = document.getElementById("checkout-form");

    // Validate form first
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const formData = new FormData(form);

    // Hide checkout modal
    const checkoutModal = document.getElementById("checkout-modal");
    checkoutModal.classList.add("hidden");
    checkoutModal.classList.remove("flex");

    // Show preview modal
    const previewModal = document.getElementById("order-preview-modal");
    previewModal.classList.remove("hidden");
    previewModal.classList.add("flex");

    // Populate customer information
    document.getElementById("preview-customer-name").textContent =
        formData.get("customer_name");
    document.getElementById("preview-customer-phone").textContent =
        formData.get("wa_number");

    // Get location name from select
    const locationSelect = document.getElementById("pickup-location");
    const selectedLocation =
        locationSelect.options[locationSelect.selectedIndex].text;
    document.getElementById("preview-pickup-location").textContent =
        selectedLocation;

    // Get pickup time
    const timeSelect = document.getElementById("pickup-time");
    const selectedTime = timeSelect ? timeSelect.value : "";
    document.getElementById("preview-pickup-time").textContent =
        selectedTime || "-";

    // Get formatted pickup date
    const pickupDateDisplay = document.getElementById(
        "pickup-date-display"
    ).textContent;
    document.getElementById("preview-pickup-date").textContent =
        pickupDateDisplay;

    // Handle notes
    const notes = formData.get("note");
    const notesContainer = document.getElementById("preview-notes-container");
    const notesElement = document.getElementById("preview-notes");
    if (notes && notes.trim() !== "") {
        notesContainer.classList.remove("hidden");
        notesElement.textContent = notes;
    } else {
        notesContainer.classList.add("hidden");
    }

    // Populate order items
    populatePreviewItems();
}

function closeOrderPreview() {
    const modal = document.getElementById("order-preview-modal");
    modal.classList.add("hidden");
    modal.classList.remove("flex");
}

function backToCheckoutForm() {
    // Hide preview modal
    const previewModal = document.getElementById("order-preview-modal");
    previewModal.classList.add("hidden");
    previewModal.classList.remove("flex");

    // Show checkout modal
    const checkoutModal = document.getElementById("checkout-modal");
    checkoutModal.classList.remove("hidden");
    checkoutModal.classList.add("flex");
}

function populatePreviewItems() {
    const previewItems = document.getElementById("preview-items");
    const previewTotalItems = document.getElementById("preview-total-items");
    const previewTotalPrice = document.getElementById("preview-total-price");

    // Calculate totals
    const totalQty = cart.reduce((sum, item) => sum + item.quantity, 0);
    const total = cart.reduce(
        (sum, item) => sum + item.price * item.quantity,
        0
    );

    // Update totals
    previewTotalItems.textContent = totalQty;
    previewTotalPrice.textContent = total.toLocaleString("id-ID");

    // Populate items
    previewItems.innerHTML = cart
        .map(
            (item) => `
        <div class="bg-white border border-gray-200 rounded-lg p-3">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h4 class="font-medium text-gray-800">${item.name}</h4>
                    <div class="flex items-center mt-1 text-sm text-gray-600">
                        <span>Rp ${item.price.toLocaleString("id-ID")}</span>
                        <span class="mx-2">×</span>
                        <span>${item.quantity}</span>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-primary-600">
                        Rp ${(item.price * item.quantity).toLocaleString(
                            "id-ID"
                        )}
                    </p>
                </div>
            </div>
        </div>
    `
        )
        .join("");
}

function confirmOrder() {
    // Show loading state
    const confirmBtn = document.querySelector(
        "#order-preview-modal button[onclick='confirmOrder()']"
    );
    const originalText = confirmBtn.innerHTML;
    confirmBtn.innerHTML =
        '<svg class="w-4 h-4 inline mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Memproses...';
    confirmBtn.disabled = true;

    // Submit the order
    submitOrder().finally(() => {
        // Reset button state
        confirmBtn.innerHTML = originalText;
        confirmBtn.disabled = false;
    });
}

// Load locations from API
async function loadLocations() {
    try {
        const response = await fetch("/api/locations");
        const data = await response.json();

        const locationSelect = document.getElementById("pickup-location");
        if (locationSelect && data.status === "success") {
            locationSelect.innerHTML =
                '<option value="">Pilih lokasi pickup...</option>';
            data.data.forEach((location) => {
                locationSelect.innerHTML += `<option value="${location.id}">${location.name}</option>`;
            });
        }
    } catch (error) {
        console.error("Error loading locations:", error);
    }
}

// Submit order to backend
async function submitOrder() {
    const form = document.getElementById("checkout-form");
    const formData = new FormData(form);

    // Prepare order data
    const orderData = {
        customer_name: formData.get("customer_name"),
        wa_number: formData.get("wa_number"),
        location_id: formData.get("id_location"),
        pickup_time: formData.get("pickup_time"),
        pick_up_date: formData.get("pick_up_date"),
        note: formData.get("note") || "",
        items: cart.map((item) => ({
            menu_id: item.id,
            qty: item.quantity,
        })),
    };

    try {
        const response = await fetch("/api/order", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN":
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content") || "",
            },
            body: JSON.stringify(orderData),
        });

        const result = await response.json();

        if (result.status === "success") {
            // Success - show confirmation
            alert(
                `Pesanan berhasil dibuat!\n\nNomor Pesanan: #${result.transaction_id}\n\nTerima kasih telah berbelanja di CalsFine!\nAnda akan dihubungi melalui WhatsApp untuk konfirmasi.`
            );

            // Clear cart and close modals
            cart = [];
            updateCartDisplay();
            closeOrderPreview();
            closeCheckoutModal();

            // Close cart if open
            if (isCartOpen) {
                toggleCart();
            }
        } else {
            throw new Error(result.message || "Terjadi kesalahan");
        }
    } catch (error) {
        console.error("Order submission error:", error);
        alert("Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.");
        throw error; // Re-throw for proper error handling in confirmOrder
    }
}
