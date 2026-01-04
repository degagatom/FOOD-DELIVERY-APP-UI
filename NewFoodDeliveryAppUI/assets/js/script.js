/**
 * Food Delivery App - Main JavaScript File
 * Handles cart functionality, search, mobile menu, and filters
 */

// ========== Mobile Menu Toggle ==========
function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    if (mobileMenu) {
        mobileMenu.classList.toggle('active');
    }
}

// Close mobile menu when clicking outside
document.addEventListener('click', function(event) {
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    
    if (mobileMenu && mobileMenuToggle && 
        !mobileMenu.contains(event.target) && 
        !mobileMenuToggle.contains(event.target)) {
        mobileMenu.classList.remove('active');
    }
});

// ========== Search Functionality ==========
function handleSearch() {
    const searchInput = document.getElementById('searchInput') || document.getElementById('searchInputMobile');
    const searchTerm = searchInput ? searchInput.value.trim().toLowerCase() : '';
    
    if (searchTerm) {
        // Redirect to restaurant listing page with search query
        window.location.href = 'restaurant-listing.php?search=' + encodeURIComponent(searchTerm);
    }
}

// Handle Enter key in search input
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchInputMobile = document.getElementById('searchInputMobile');
    
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                handleSearch();
            }
        });
    }
    
    if (searchInputMobile) {
        searchInputMobile.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                handleSearch();
            }
        });
    }
});

// ========== Add to Cart Function ==========
function addToCart(itemId, itemName, itemPrice, restaurant) {
    // Check if user is logged in (basic check)
    // In a real app, you would check session on server side
    
    // Create form data
    const formData = new FormData();
    formData.append('itemId', itemId);
    formData.append('itemName', itemName);
    formData.append('itemPrice', itemPrice);
    formData.append('restaurant', restaurant);
    
    // Send AJAX request to add item to cart
    fetch('add-to-cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification('Item added to cart!', 'success');
            
            // Update cart badge if it exists
            updateCartBadge(data.cartCount);
            
            // If on cart page, reload to show updated cart
            if (window.location.pathname.includes('cart.php')) {
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            }
        } else {
            showNotification(data.message || 'Failed to add item to cart.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    });
}

// ========== Update Cart Quantity ==========
function updateQuantity(index, change) {
    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('index', index);
    
    // Get current quantity from the page
    const quantityElement = document.querySelector(`[data-index="${index}"] .quantity`);
    let currentQuantity = quantityElement ? parseInt(quantityElement.textContent) : 1;
    currentQuantity += change;
    
    if (currentQuantity < 1) {
        removeFromCart(index);
        return;
    }
    
    formData.append('quantity', currentQuantity);
    
    // Send AJAX request
    fetch('update-cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart display
            updateCartDisplay(data);
            
            // Update cart badge
            updateCartBadge(data.cartCount);
        } else {
            showNotification(data.message || 'Failed to update cart.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    });
}

// ========== Remove from Cart ==========
function removeFromCart(index) {
    if (!confirm('Are you sure you want to remove this item from your cart?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('action', 'remove');
    formData.append('index', index);
    
    // Send AJAX request
    fetch('update-cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove item from DOM
            const cartItem = document.querySelector(`[data-index="${index}"]`);
            if (cartItem) {
                cartItem.style.transition = 'opacity 0.3s ease';
                cartItem.style.opacity = '0';
                setTimeout(() => {
                    cartItem.remove();
                    
                    // If cart is empty, reload page to show empty cart message
                    if (data.cartCount === 0) {
                        window.location.reload();
                    } else {
                        // Update totals
                        updateCartDisplay(data);
                    }
                }, 300);
            }
            
            // Update cart badge
            updateCartBadge(data.cartCount);
        } else {
            showNotification(data.message || 'Failed to remove item.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    });
}

// ========== Update Cart Display ==========
function updateCartDisplay(data) {
    // Update subtotal
    const subtotalElement = document.getElementById('subtotal');
    if (subtotalElement) {
        subtotalElement.textContent = '$' + data.subtotal;
    }
    
    // Update tax
    const taxElement = document.getElementById('tax');
    if (taxElement) {
        taxElement.textContent = '$' + data.tax;
    }
    
    // Update total
    const totalElement = document.getElementById('cartTotal');
    if (totalElement) {
        totalElement.textContent = '$' + data.total;
    }
    
    // Update item totals
    const cartItems = document.querySelectorAll('.cart-item');
    cartItems.forEach(item => {
        const index = parseInt(item.getAttribute('data-index'));
        const quantityElement = item.querySelector('.quantity');
        const priceElement = item.querySelector('.cart-item-price');
        const itemTotalElement = item.querySelector('.item-total');
        
        if (quantityElement && priceElement && itemTotalElement) {
            const quantity = parseInt(quantityElement.textContent);
            const price = parseFloat(priceElement.textContent.replace('$', ''));
            const itemTotal = (quantity * price).toFixed(2);
            itemTotalElement.textContent = '$' + itemTotal;
        }
    });
}

// ========== Update Cart Badge ==========
function updateCartBadge(count) {
    const cartBadges = document.querySelectorAll('.cart-badge');
    cartBadges.forEach(badge => {
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    });
}

// ========== Filter Restaurants by Category ==========
function filterRestaurants(category) {
    const restaurantCards = document.querySelectorAll('.restaurant-card');
    const filterTabs = document.querySelectorAll('.filter-tab');
    
    // Update active filter tab
    filterTabs.forEach(tab => {
        tab.classList.remove('active');
        if (tab.textContent.toLowerCase().includes(category) || 
            (category === 'all' && tab.textContent.toLowerCase() === 'all')) {
            tab.classList.add('active');
        }
    });
    
    // Filter restaurant cards
    restaurantCards.forEach(card => {
        if (category === 'all') {
            card.style.display = 'block';
        } else {
            const cardCategory = card.getAttribute('data-category');
            if (cardCategory === category) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        }
    });
}

// ========== Filter by Category from Home Page ==========
function filterByCategory(category) {
    // Redirect to restaurant listing page with category filter
    window.location.href = 'restaurant-listing.php?category=' + category;
}

// ========== Apply URL Parameters ==========
document.addEventListener('DOMContentLoaded', function() {
    // Check for category filter in URL
    const urlParams = new URLSearchParams(window.location.search);
    const category = urlParams.get('category');
    const search = urlParams.get('search');
    
    if (category) {
        // Wait a bit for DOM to be ready
        setTimeout(() => {
            filterRestaurants(category);
        }, 100);
    }
    
    if (search) {
        // Filter restaurants by search term
        const searchTerm = search.toLowerCase();
        const restaurantCards = document.querySelectorAll('.restaurant-card');
        
        restaurantCards.forEach(card => {
            const restaurantName = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const restaurantCuisine = card.querySelector('.restaurant-cuisine')?.textContent.toLowerCase() || '';
            
            if (restaurantName.includes(searchTerm) || restaurantCuisine.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
        
        // Set search input value
        const searchInput = document.getElementById('searchInput') || document.getElementById('searchInputMobile');
        if (searchInput) {
            searchInput.value = search;
        }
    }
});

// ========== Notification System ==========
function showNotification(message, type = 'success') {
    // Remove existing notification if any
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background-color: ${type === 'success' ? '#00b894' : '#d63031'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 10000;
        animation: slideIn 0.3s ease;
        max-width: 300px;
        font-weight: 500;
    `;
    
    // Add animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);
    
    // Add to page
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideIn 0.3s ease reverse';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// ========== Smooth Scroll ==========
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// ========== Form Validation ==========
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#d63031';
                    
                    // Reset border color on input
                    field.addEventListener('input', function() {
                        this.style.borderColor = '';
                    });
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showNotification('Please fill in all required fields.', 'error');
            }
        });
    });
});

// ========== Initialize on Page Load ==========
document.addEventListener('DOMContentLoaded', function() {
    // Initialize any page-specific functionality
    console.log('Food Delivery App initialized');
    
    // Update cart badge on page load
    // This would typically come from server-side session
    // For now, we'll rely on PHP to set the initial count
});

