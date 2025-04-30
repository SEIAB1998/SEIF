// Load products on homepage
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('products-container')) {
        fetchProducts();
    }
});

function fetchProducts() {
    fetch('get_products.php')
        .then(response => response.json())
        .then(products => {
            const container = document.getElementById('products-container');
            container.innerHTML = '';

            products.forEach(product => {
                const productCard = document.createElement('div');
                productCard.className = 'product-card';
                productCard.innerHTML = `
                    <div class="product-image">
                        ${product.image_path ? `<img src="${product.image_path}" alt="${product.name}">` : 'Image non disponible'}
                    </div>
                    <div class="product-info">
                        <h3>${product.name}</h3>
                        <p>${product.description.substring(0, 50)}...</p>
                        <p class="price">${product.price} €</p>
                    </div>
                `;
                container.appendChild(productCard);
            });
        })
        .catch(error => console.error('Error:', error));
}

// Registration form validation
if (document.getElementById('register-form')) {
    document.getElementById('register-form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm-password').value;

        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas!');
        }
    });
}

// Search functionality
function setupSearch() {
    const searchForm = document.getElementById('search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = document.getElementById('search-query').value;
            window.location.href = `products.php?search=${encodeURIComponent(query)}`;
        });
    }
}

// Call this in DOMContentLoaded
setupSearch();


function renderProducts(products, container) {
    container.innerHTML = '';

    products.forEach(product => {
        const productCard = document.createElement('div');
        productCard.className = 'product-card';
        productCard.innerHTML = `
            <div class="product-image">
                ${product.image_path ? `<img src="${product.image_path}" alt="${product.name}">` : 'Image non disponible'}
            </div>
            <div class="product-info">
                <h3>${product.name}</h3>
                <p>${product.description.substring(0, 50)}...</p>
                <p class="price">${product.price} €</p>
                <button class="btn-small" onclick="addToCart(${product.id})">Ajouter au panier</button>
            </div>
        `;
        container.appendChild(productCard);
    });
}

function addToCart(productId) {
    fetch('add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Produit ajouté au panier!');
        } else {
            alert('Veuillez vous connecter pour ajouter des articles au panier.');
            window.location.href = 'login.php';
        }
    });
}

// Password toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.querySelector('.toggle-password');
    const passwordInput = document.getElementById('password');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';

            // Update icon
            this.innerHTML = isPassword ?
                `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                </svg>` :
                `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>`;
        });
    }

    // Form submission handling
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = '<span>Connexion en cours...</span>';

            // Here you would normally handle the form submission
            // For demo, we'll just simulate a delay
            setTimeout(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = '<span>Se connecter</span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"></path></svg>';
            }, 2000);
        });
    }
});

function addToCart(productId) {
    // send the product ID to your PHP handler
    fetch('add_to_cart.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `product_id=${encodeURIComponent(productId)}`
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        // optional: update a cart counter in the UI
        alert('Produit ajouté au panier !');
      } else {
        alert('Erreur : ' + (data.message || 'Impossible d’ajouter.'));
      }
    })
    .catch(err => {
      console.error(err);
      alert('Erreur réseau. Réessayez.');
    });
  }

// Function to edit a product
function editProduct(productId) {
  window.location.href = `edit_product.php?id=${productId}`;
}

// Function to delete a product
function deleteProduct(productId) {
  console.log('Delete product function called with ID:', productId);

  if (confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
    console.log('User confirmed deletion');

    fetch('delete_product.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ product_id: productId })
    })
    .then(res => {
      console.log('Response status:', res.status);
      return res.json();
    })
    .then(data => {
      console.log('Response data:', data);

      if (data.success) {
        alert('Produit supprimé avec succès !');
        // Refresh the products list
        fetch('get_user_products.php')
          .then(response => response.json())
          .then(products => {
            console.log('Products after deletion:', products);
            const container = document.getElementById('user-products');
            renderProducts(products, container);
          })
          .catch(err => {
            console.error('Error fetching products after deletion:', err);
          });
      } else {
        alert('Erreur : ' + (data.message || 'Impossible de supprimer le produit.'));
      }
    })
    .catch(err => {
      console.error('Error during product deletion:', err);
      alert('Erreur réseau. Réessayez.');
    });
  } else {
    console.log('User cancelled deletion');
  }
}