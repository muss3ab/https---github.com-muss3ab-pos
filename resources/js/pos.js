document.addEventListener('DOMContentLoaded', function() {
    let cart = [];

    // Add to cart
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', function() {
            const productId = this.dataset.id;
            addToCart(productId);
        });
    });

    function addToCart(productId) {
        // Make API call to get product details
        fetch(`/api/products/${productId}`)
            .then(response => response.json())
            .then(product => {
                const existingItem = cart.find(item => item.id === product.id);

                if (existingItem) {
                    existingItem.quantity += 1;
                } else {
                    cart.push({
                        id: product.id,
                        name: product.name,
                        price: product.price,
                        quantity: 1
                    });
                }

                updateCartDisplay();
            });
    }

    function updateCartDisplay() {
        const cartContainer = document.getElementById('cart-items');
        cartContainer.innerHTML = '';

        let total = 0;

        cart.forEach(item => {
            const subtotal = item.price * item.quantity;
            total += subtotal;

            cartContainer.innerHTML += `
                <div class="mb-2 cart-item">
                    <div class="d-flex justify-content-between">
                        <span>${item.name}</span>
                        <span>$${subtotal.toFixed(2)}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-secondary minus-btn" data-id="${item.id}">-</button>
                            <span class="btn btn-sm">${item.quantity}</span>
                            <button class="btn btn-sm btn-secondary plus-btn" data-id="${item.id}">+</button>
                        </div>
                        <button class="btn btn-sm btn-danger remove-btn" data-id="${item.id}">Remove</button>
                    </div>
                </div>
            `;
        });

        document.getElementById('total-amount').textContent = total.toFixed(2);
    }

    const barcodeScanner = new BarcodeScanner((barcode) => {
        // Search for product by barcode
        fetch(`/api/products/barcode/${barcode}`)
            .then(response => response.json())
            .then(product => {
                if (product) {
                    addToCart(product.id);
                } else {
                    alert('Product not found');
                }
            });
    });
});
