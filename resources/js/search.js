// Search functionality
export function searchProducts(event) {
    const searchTerm = document
        .getElementById("default-search")
        .value.toLowerCase()
        .trim();
    const productCards = document.querySelectorAll("#products-grid > div");
    let visibleCount = 0;

    productCards.forEach((card) => {
        // Skip the fallback message div
        if (card.classList.contains("col-span-full")) {
            return;
        }

        // Get product information from the card
        const productName = card.querySelector("h3").textContent.toLowerCase();
        const productDescription = card
            .querySelector("p.text-xs.text-gray-600")
            .textContent.toLowerCase();
        const categoryElement = card.querySelector("span.text-xs.bg-gray-100");
        const productCategory = categoryElement
            ? categoryElement.textContent.toLowerCase()
            : "";

        // Check if search term matches name, description, or category
        const isMatch =
            productName.includes(searchTerm) ||
            productDescription.includes(searchTerm) ||
            productCategory.includes(searchTerm);

        if (isMatch) {
            card.style.display = "block";
            visibleCount++;
        } else {
            card.style.display = "none";
        }
    });

    // Show/hide "no products found" message
    const fallbackDiv = document.querySelector("#products-grid .col-span-full");
    if (visibleCount === 0 && searchTerm !== "") {
        if (!fallbackDiv) {
            // Create fallback message if it doesn't exist
            const noResultsDiv = document.createElement("div");
            noResultsDiv.className = "col-span-full text-center py-8";
            noResultsDiv.innerHTML =
                '<p class="text-gray-500">Tidak ada produk yang ditemukan untuk pencarian "' +
                searchTerm +
                '"</p>';
            document.getElementById("products-grid").appendChild(noResultsDiv);
        } else {
            fallbackDiv.innerHTML =
                '<p class="text-gray-500">Tidak ada produk yang ditemukan untuk pencarian "' +
                searchTerm +
                '"</p>';
            fallbackDiv.style.display = "block";
        }
    } else if (fallbackDiv && searchTerm === "") {
        // Hide fallback message when search is cleared
        fallbackDiv.style.display = "none";
    } else if (fallbackDiv && visibleCount > 0) {
        // Hide fallback message when products are found
        fallbackDiv.style.display = "none";
    }
}

// Clear search function
export function clearSearch() {
    document.getElementById("default-search").value = "";
    searchProducts({ type: "input", target: { value: "" } });
}

// Initialize search functionality when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    // Make functions available globally for onclick handlers
    window.searchProducts = searchProducts;
    window.clearSearch = clearSearch;

    const searchInput = document.getElementById("default-search");

    // Add event listener for Escape key to clear search
    searchInput.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            clearSearch();
        }
    });
});
