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
    window.showOrderSuccessModal = showOrderSuccessModal;
    window.closeOrderSuccessModal = closeOrderSuccessModal;
    window.downloadQRCode = downloadQRCode;
    window.showDownloadSuccess = showDownloadSuccess;
    window.copyPickupCode = copyPickupCode;
    window.showPaymentPendingModal = showPaymentPendingModal;
    window.closePaymentPendingModal = closePaymentPendingModal;
    window.retryPayment = retryPayment;

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
        customer_email: formData.get("customer_email"),
        location_id: formData.get("location_id"),
        pickup_time: formData.get("pickup_time"),
        pick_up_date: formData.get("pick_up_date"),
        note: formData.get("note") || "",
        items: cart.map((item) => ({
            menu_id: item.id,
            qty: item.quantity,
        })),
    };

    try {
        const response = await fetch("/order", {
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
            // Close preview modal first
            closeOrderPreview();

            // Open Midtrans Snap payment
            if (result.snap_token) {
                window.snap.pay(result.snap_token, {
                    onSuccess: function (paymentResult) {
                        console.log("Payment success:", paymentResult);

                        // Show success modal with QR Code
                        showOrderSuccessModal(
                            result.transaction_id,
                            result.qr_code,
                            result.pickup_code
                        );

                        // Clear cart and close modals
                        cart = [];
                        updateCartDisplay();
                        closeCheckoutModal();

                        // Close cart if open
                        if (isCartOpen) {
                            toggleCart();
                        }
                    },
                    onPending: function (paymentResult) {
                        console.log("Payment pending:", paymentResult);
                        alert(
                            "Pembayaran sedang diproses. Silakan selesaikan pembayaran Anda."
                        );

                        // Show pending payment modal or redirect
                        showPaymentPendingModal(result.transaction_id);
                    },
                    onError: function (paymentResult) {
                        console.log("Payment error:", paymentResult);
                        alert("Pembayaran gagal. Silakan coba lagi.");
                    },
                    onClose: function () {
                        console.log("Payment popup closed");
                        alert(
                            "Anda menutup popup pembayaran sebelum selesai. Pesanan masih tersimpan dan Anda dapat melakukan pembayaran nanti."
                        );

                        // Show order with pending payment status
                        showPaymentPendingModal(result.transaction_id);
                    },
                });
            } else {
                throw new Error("Token pembayaran tidak ditemukan");
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

// Function to show order success modal with QR Code
function showOrderSuccessModal(transactionId, qrCodeDataUri, pickupCode) {
    // Create modal HTML if it doesn't exist
    let modal = document.getElementById("order-success-modal");
    if (!modal) {
        const modalHtml = `
            <div id="order-success-modal" class="fixed inset-0 bg-neutral-900/25 z-60 hidden items-center justify-center p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
                    <!-- Modal Header -->
                    <div class="bg-green-500 text-white p-4 rounded-t-lg">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold">✅ Pesanan Berhasil!</h2>
                            <button onclick="closeOrderSuccessModal()" class="text-white hover:text-gray-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Content -->
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-semibold mb-2">Nomor Pesanan</h3>
                        <p class="text-2xl font-bold text-primary-600 mb-2">#<span id="order-number"></span></p>
                        <div class="flex items-center justify-center mb-4">
                            <p class="text-lg font-semibold text-green-600 mr-2">Pickup Code: <span id="pickup-code" class="font-mono text-xl"></span></p>
                            <button onclick="copyPickupCode()" class="ml-2 p-1 text-gray-500 hover:text-gray-700 rounded" title="Copy Pickup Code">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-gray-600 mb-4">Simpan QR Code ini untuk pickup pesanan Anda:</p>
                            <div class="flex justify-center mb-4">
                                <img id="qr-code-image" src="" alt="QR Code Pickup" class="max-w-[200px] border border-gray-200 rounded">
                            </div>
                            <button onclick="downloadQRCode()" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition font-medium mb-3 flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download QR Code
                            </button>
                        </div>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-blue-800">Penting!</p>
                                    <p class="text-sm text-blue-700 mt-1">
                                        • Simpan QR Code atau Pickup Code dengan aman<br>
                                        • QR Code dapat di-download untuk akses offline<br>
                                        • Pickup Code dapat disalin untuk kemudahan
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                            <p class="text-sm text-yellow-700">
                                <strong>Cara Pickup:</strong><br>
                                1. Datang ke lokasi sesuai waktu yang dipilih<br>
                                2. Tunjukkan QR Code ini ke admin atau berikan Pickup Code<br>
                                3. Pesanan akan dikonfirmasi sebagai selesai<br><br>
                                <strong>💡 Tips:</strong> Download QR Code untuk memudahkan akses offline
                            </p>
                        </div>
                        
                        <button onclick="closeOrderSuccessModal()" class="w-full bg-primary-500 text-white py-3 px-4 rounded-lg hover:bg-primary-600 transition font-semibold">
                            Mengerti
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML("beforeend", modalHtml);
        modal = document.getElementById("order-success-modal");
    }

    // Update modal content
    document.getElementById("order-number").textContent = transactionId;
    document.getElementById("pickup-code").textContent = pickupCode || "";
    document.getElementById("qr-code-image").src = qrCodeDataUri;

    // Show modal
    modal.classList.remove("hidden");
    modal.classList.add("flex");
}

// Function to close order success modal
function closeOrderSuccessModal() {
    const modal = document.getElementById("order-success-modal");
    if (modal) {
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    }
}

// Function to download QR Code
function downloadQRCode() {
    const qrCodeImage = document.getElementById("qr-code-image");
    const pickupCode = document.getElementById("pickup-code").textContent;
    const orderNumber = document.getElementById("order-number").textContent;
    const downloadBtn = document.querySelector(
        'button[onclick="downloadQRCode()"]'
    );

    if (!qrCodeImage || !qrCodeImage.src) {
        alert("QR Code tidak tersedia untuk didownload");
        return;
    }

    // Show loading state
    const originalBtnContent = downloadBtn.innerHTML;
    downloadBtn.innerHTML = `
        <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        Mendownload...
    `;
    downloadBtn.disabled = true;

    try {
        // Create a canvas element
        const canvas = document.createElement("canvas");
        const ctx = canvas.getContext("2d");

        // Create a new image from the QR code
        const img = new Image();
        img.crossOrigin = "anonymous"; // Handle CORS if needed

        img.onload = function () {
            // Set canvas size to match image
            canvas.width = img.width;
            canvas.height = img.height;

            // Draw the image on canvas
            ctx.drawImage(img, 0, 0);

            // Convert canvas to blob and download
            canvas.toBlob(function (blob) {
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement("a");
                link.href = url;
                link.download = `QR-Pickup-Order-${orderNumber}-${pickupCode}.png`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);

                // Show success feedback
                showDownloadSuccess(downloadBtn, originalBtnContent);
            }, "image/png");
        };

        img.onerror = function () {
            // Fallback: try direct download using data URI
            const link = document.createElement("a");
            link.href = qrCodeImage.src;
            link.download = `QR-Pickup-Order-${orderNumber}-${pickupCode}.png`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Show success feedback
            showDownloadSuccess(downloadBtn, originalBtnContent);
        };

        img.src = qrCodeImage.src;
    } catch (error) {
        console.error("Error downloading QR Code:", error);

        // Reset button state
        downloadBtn.innerHTML = originalBtnContent;
        downloadBtn.disabled = false;

        // Fallback method: open in new tab
        try {
            const link = document.createElement("a");
            link.href = qrCodeImage.src;
            link.download = `QR-Pickup-Order-${orderNumber}-${pickupCode}.png`;
            link.target = "_blank";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Show success feedback
            showDownloadSuccess(downloadBtn, originalBtnContent);
        } catch (fallbackError) {
            console.error("Fallback download also failed:", fallbackError);
            alert(
                "Tidak dapat mendownload QR Code. Silakan screenshot gambar QR Code secara manual."
            );
        }
    }
}

// Helper function to show download success feedback
function showDownloadSuccess(button, originalContent) {
    // Show success state
    button.innerHTML = `
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        Download Berhasil!
    `;
    button.classList.remove("bg-blue-500", "hover:bg-blue-600");
    button.classList.add("bg-green-500", "hover:bg-green-600");

    // Reset to original state after 3 seconds
    setTimeout(() => {
        button.innerHTML = originalContent;
        button.disabled = false;
        button.classList.remove("bg-green-500", "hover:bg-green-600");
        button.classList.add("bg-blue-500", "hover:bg-blue-600");
    }, 3000);
}

// Function to copy pickup code to clipboard
function copyPickupCode() {
    const pickupCodeElement = document.getElementById("pickup-code");
    const pickupCode = pickupCodeElement.textContent;

    if (!pickupCode) {
        alert("Pickup code tidak tersedia");
        return;
    }

    // Try to use the Clipboard API first
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard
            .writeText(pickupCode)
            .then(function () {
                showCopySuccess();
            })
            .catch(function (err) {
                console.error("Failed to copy using Clipboard API: ", err);
                fallbackCopyTextToClipboard(pickupCode);
            });
    } else {
        // Fallback for older browsers
        fallbackCopyTextToClipboard(pickupCode);
    }
}

// Fallback copy function for older browsers
function fallbackCopyTextToClipboard(text) {
    const textArea = document.createElement("textarea");
    textArea.value = text;

    // Avoid scrolling to bottom
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";

    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
        const successful = document.execCommand("copy");
        if (successful) {
            showCopySuccess();
        } else {
            alert(
                "Tidak dapat menyalin pickup code. Silakan salin secara manual: " +
                    text
            );
        }
    } catch (err) {
        console.error("Fallback: Oops, unable to copy", err);
        alert(
            "Tidak dapat menyalin pickup code. Silakan salin secara manual: " +
                text
        );
    }

    document.body.removeChild(textArea);
}

// Function to show copy success feedback
function showCopySuccess() {
    const copyButton = document.querySelector(
        'button[onclick="copyPickupCode()"]'
    );
    const originalColor = copyButton.className;

    // Show success feedback
    copyButton.innerHTML = `
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
    `;
    copyButton.className =
        "ml-2 p-1 text-green-500 hover:text-green-700 rounded";
    copyButton.title = "Pickup Code disalin!";

    // Reset after 2 seconds
    setTimeout(() => {
        copyButton.innerHTML = `
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
            </svg>
        `;
        copyButton.className = originalColor;
        copyButton.title = "Copy Pickup Code";
    }, 2000);
}

// Function to show payment pending modal
function showPaymentPendingModal(transactionId) {
    // Create modal HTML if it doesn't exist
    let modal = document.getElementById("payment-pending-modal");
    if (!modal) {
        const modalHtml = `
            <div id="payment-pending-modal" class="fixed inset-0 bg-neutral-900/25 z-60 hidden items-center justify-center p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
                    <!-- Modal Header -->
                    <div class="bg-yellow-500 text-white p-4 rounded-t-lg">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold">⏳ Pembayaran Tertunda</h2>
                            <button onclick="closePaymentPendingModal()" class="text-white hover:text-gray-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Content -->
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-semibold mb-2">Pesanan Telah Dibuat</h3>
                        <p class="text-2xl font-bold text-primary-600 mb-4">#<span id="pending-order-number"></span></p>
                        
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                            <p class="text-sm text-yellow-700">
                                <strong>Status:</strong> Menunggu Pembayaran<br><br>
                                Pesanan Anda sudah dibuat namun pembayaran belum selesai. 
                                Silakan selesaikan pembayaran untuk mengkonfirmasi pesanan.
                            </p>
                        </div>
                        
                        <div class="space-y-3">
                            <button onclick="retryPayment()" class="w-full bg-primary-500 text-white py-3 px-4 rounded-lg hover:bg-primary-600 transition font-semibold">
                                Lanjutkan Pembayaran
                            </button>
                            <button onclick="closePaymentPendingModal()" class="w-full bg-gray-200 text-gray-800 py-3 px-4 rounded-lg hover:bg-gray-300 transition font-medium">
                                Nanti Saja
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML("beforeend", modalHtml);
        modal = document.getElementById("payment-pending-modal");
    }

    // Update modal content
    document.getElementById("pending-order-number").textContent = transactionId;

    // Show modal
    modal.classList.remove("hidden");
    modal.classList.add("flex");

    // Clear cart since order is created
    cart = [];
    updateCartDisplay();
    closeCheckoutModal();

    // Close cart if open
    if (isCartOpen) {
        toggleCart();
    }
}

// Function to close payment pending modal
function closePaymentPendingModal() {
    const modal = document.getElementById("payment-pending-modal");
    if (modal) {
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    }
}

// Function to retry payment
function retryPayment() {
    const transactionId = document.getElementById(
        "pending-order-number"
    ).textContent;

    // Close pending modal
    closePaymentPendingModal();

    // You could implement a way to get the snap token again or redirect to payment page
    // For now, let's show an alert
    alert(
        "Fitur retry payment akan segera tersedia. Silakan hubungi admin untuk melanjutkan pembayaran."
    );
}
