// Function to add products to the cart
function addToCart(productId, quantity) {
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', quantity);

    fetch('add_to_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
    })
    .catch(error => console.error('Error:', error));
}

// Function to search for products
function searchProducts() {
    const searchQuery = document.getElementById('search').value;

    fetch('search.php?search=' + searchQuery)
        .then(response => response.json())
        .then(data => {
            displayProducts(data);
        })
        .catch(error => console.error('Error:', error));
}

// Function to display products
function displayProducts(products) {
    const productContainer = document.getElementById('product-container');
    productContainer.innerHTML = '';

    products.forEach(product => {
        const productCard = document.createElement('div');
        productCard.classList.add('product-card');

        productCard.innerHTML = `
            <img src="${product.image}" alt="${product.name}">
            <h3>${product.name}</h3>
            <p>$${product.price}</p>
            <button onclick="addToCart(${product.id}, 1)">Add to Cart</button>
        `;

        productContainer.appendChild(productCard);
    });
}

// Initial fetch to display all products on page load
document.addEventListener('DOMContentLoaded', function() {
    searchProducts();
});
