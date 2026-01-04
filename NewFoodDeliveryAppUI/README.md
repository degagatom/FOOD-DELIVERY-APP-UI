# Food Delivery App UI

A fully responsive food delivery application built with HTML5, CSS3, Vanilla JavaScript, and PHP. This project features a modern, mobile-first design inspired by popular food delivery apps like Zomato, Uber Eats, and DoorDash.

## Features

- 🍔 **Modern UI Design** - Clean, attractive interface with smooth animations
- 📱 **Fully Responsive** - Mobile-first design that works on all devices
- 🛒 **Shopping Cart** - Dynamic cart with add, remove, and quantity update functionality
- 🔍 **Search & Filter** - Search restaurants and filter by category
- 👤 **User Authentication** - Login and registration system (session-based)
- 💳 **Checkout Process** - Complete checkout flow with address and payment options
- 🎨 **Modern Styling** - Uses CSS Grid, Flexbox, and modern design patterns

## Project Structure

```
NewFoodDeliveryAppUI/
├── index.php                 # Home page
├── restaurant-listing.php    # Restaurant listing with filters
├── restaurant-detail.php     # Individual restaurant menu page
├── cart.php                  # Shopping cart page
├── checkout.php              # Checkout page
├── order-confirmation.php    # Order confirmation page
├── login.php                 # User login page
├── register.php              # User registration page
├── logout.php                # Logout handler
├── process-checkout.php      # Checkout form processor (saves to database)
├── add-to-cart.php           # AJAX endpoint for adding items to cart
├── update-cart.php           # AJAX endpoint for updating cart
├── config.php                # Database configuration
├── database.sql              # MySQL database schema and sample data
├── init-database.php         # Database initialization script
├── includes/
│   ├── header.php            # Site header with navigation
│   └── footer.php            # Site footer
├── assets/
│   ├── css/
│   │   └── style.css         # Main stylesheet
│   ├── js/
│   │   └── script.js         # Main JavaScript file
│   └── images/               # Image assets directory
└── README.md                 # This file
```

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher (or MariaDB 10.2+)
- Web server (Apache, Nginx, or PHP built-in server)
- Modern web browser

## Installation

1. **Clone or download** this repository to your web server directory

2. **Configure Database**:
   - Open `config.php` and update database credentials:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'food_delivery_db');
     ```

3. **Initialize Database**:
   - Option 1: Run the initialization script in your browser:
     ```
     http://localhost:8000/init-database.php
     ```
   - Option 2: Import `database.sql` manually using phpMyAdmin or MySQL command line:
     ```bash
     mysql -u root -p < database.sql
     ```

4. **Start PHP built-in server** (for local development):
   ```bash
   php -S localhost:8000
   ```

5. **Or use your existing web server** (Apache/Nginx):
   - Place files in your web root directory
   - Ensure PHP and MySQL extensions are enabled
   - Access via your server URL

6. **Open in browser**:
   - Local: `http://localhost:8000`
   - Or your server URL

## Usage

### User Registration & Login
- Register a new account using the registration page
- Login with your registered email and password
- User data is stored in MySQL database with password hashing

### Page Descriptions

#### 1. **Home Page** (`index.php`)
The landing page of the application that welcomes users and provides an overview of available food options. It features:
- **Hero Section**: A prominent banner with a compelling headline "Delicious Food, Delivered Fast" and a call-to-action button that directs users to browse restaurants
- **Food Categories Grid**: Interactive category cards (Pizza, Burgers, Sushi, Chinese, Indian, Drinks, Desserts, Salads) that allow users to filter restaurants by cuisine type
- **Featured Restaurants**: A curated selection of top-rated restaurants displayed in a grid layout, showing restaurant name, cuisine type, star rating, and estimated delivery time
- **Popular Food Items**: A showcase of trending menu items from various restaurants with quick "Add to Cart" functionality for instant ordering

#### 2. **Restaurant Listing Page** (`restaurant-listing.php`)
A comprehensive directory of all available restaurants in the system. This page enables users to:
- **Browse All Restaurants**: View all active restaurants in a responsive grid layout with restaurant icons, names, ratings, and delivery times
- **Category Filtering**: Filter restaurants by cuisine type using tab buttons (All, Pizza, Burgers, Sushi, Chinese, Indian) for quick navigation
- **Restaurant Cards**: Each card displays essential information including restaurant name, cuisine type, star rating, estimated delivery time, and a "Popular" badge for featured restaurants
- **Quick Navigation**: Click on any restaurant card to view its detailed menu and place orders

#### 3. **Restaurant Detail Page** (`restaurant-detail.php`)
A dedicated page for each restaurant that provides detailed information and menu browsing. Features include:
- **Restaurant Banner**: Large header section displaying the restaurant's icon, name, cuisine type, rating, and delivery time
- **Menu Display**: Complete menu organized by categories (Popular, Appetizers, Main Courses, Desserts, etc.)
- **Menu Items**: Each item shows name, description, price, and an "Add to Cart" button
- **Dynamic Menu Loading**: Menu items are loaded from the database and grouped by category for easy navigation
- **Add to Cart**: One-click functionality to add items directly to the shopping cart with quantity selection

#### 4. **Shopping Cart Page** (`cart.php`)
The cart management page where users can review and modify their selected items before checkout. It includes:
- **Cart Items Display**: List of all items added to the cart with item name, restaurant name, individual price, and quantity
- **Quantity Controls**: Increase or decrease item quantities with +/- buttons, with real-time price updates
- **Item Removal**: Remove unwanted items from the cart with a single click
- **Order Summary**: Sidebar showing subtotal, delivery fee ($2.99), tax (10%), and total amount
- **Empty Cart State**: Friendly message and call-to-action when the cart is empty, encouraging users to browse restaurants
- **Proceed to Checkout**: Prominent button to continue to the checkout process

#### 5. **Checkout Page** (`checkout.php`)
The final step before order placement where users provide delivery information and payment details. This page requires:
- **User Authentication**: Automatic redirect to login page if user is not logged in
- **Delivery Address Form**: Complete address collection including full name, phone number, street address, city, state, ZIP code, and optional delivery instructions
- **Payment Method Selection**: Multiple payment options including Credit/Debit Card, Cash on Delivery, and PayPal (radio button selection)
- **Order Summary Sidebar**: Real-time display of all cart items with quantities and prices, plus breakdown of subtotal, delivery fee, tax, and final total
- **Form Validation**: Required field validation to ensure complete information before order submission
- **Order Placement**: Submit button to finalize and place the order

#### 6. **Order Confirmation Page** (`order-confirmation.php`)
A success page displayed after successful order placement. It provides:
- **Confirmation Message**: Clear success indicator with checkmark icon and thank you message
- **Order Details**: Complete order information including unique order number, delivery address, selected payment method, and total amount
- **Order Tracking**: Order number for future reference and tracking
- **Navigation Options**: Buttons to return to home page or continue ordering more items
- **Session Management**: Automatically clears order data from session after display

#### 7. **Login Page** (`login.php`)
User authentication page for existing account holders. Features:
- **Login Form**: Email and password input fields with validation
- **Error Handling**: Displays error messages for invalid credentials or empty fields
- **Session Management**: Automatically redirects logged-in users to home page
- **Password Security**: Secure password verification using PHP's password hashing
- **Registration Link**: Quick link to registration page for new users
- **User-Friendly Design**: Clean, centered form layout with clear instructions

#### 8. **Registration Page** (`register.php`)
New user account creation page. Includes:
- **Registration Form**: Fields for full name, email address, phone number, password, and password confirmation
- **Form Validation**: 
  - Checks for empty fields
  - Validates email format
  - Ensures password match
  - Enforces minimum password length (6 characters)
  - Prevents duplicate email registration
- **Password Security**: Passwords are hashed using PHP's `password_hash()` before storage
- **Auto-Login**: Automatically logs in users after successful registration
- **Error Messages**: Clear error display for validation failures
- **Login Link**: Quick access to login page for existing users

## Technical Details

### Frontend
- **HTML5** - Semantic markup
- **CSS3** - Modern styling with:
  - CSS Grid for layouts
  - Flexbox for component alignment
  - CSS Variables for theming
  - Mobile-first responsive design
  - Smooth transitions and animations

### Backend
- **PHP** - Server-side logic:
  - MySQL database integration
  - Session management
  - Form handling and validation
  - Cart persistence (sessions)
  - User authentication with password hashing
  - Order processing and storage

### JavaScript
- **Vanilla JS** - No frameworks:
  - AJAX for cart operations
  - DOM manipulation
  - Event handling
  - Search and filter functionality
  - Mobile menu toggle

## Color Scheme

- Primary: `#ff6b35` (Orange/Red)
- Secondary: `#f7931e` (Orange)
- Text Dark: `#2d3436`
- Text Light: `#636e72`
- Background: `#f8f9fa`

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Database Structure

The application uses MySQL with the following tables:

- **users** - User accounts with authentication
- **restaurants** - Restaurant information
- **menu_items** - Menu items for each restaurant
- **orders** - Order records
- **order_items** - Individual items in each order

Sample data is included in `database.sql` with:
- 6 sample restaurants
- 30+ menu items across different categories
- Ready to use for testing

## Notes

- **Database Integration**: Full MySQL integration for users, restaurants, menu items, and orders
- **Password Security**: Passwords are hashed using PHP's `password_hash()` function
- **Session Management**: Cart is stored in PHP sessions, orders are saved to database
- **Payment Processing**: UI-only (no real payment gateway integration)
- **Images**: Use emoji placeholders (replace with actual images in `assets/images/`)

## Customization

### Adding Images
Replace emoji placeholders in the HTML with actual images:
1. Add images to `assets/images/` directory
2. Update image paths in PHP files
3. Replace `<div class="image-placeholder">` elements with `<img>` tags

### Modifying Colors
Update CSS variables in `assets/css/style.css`:
```css
:root {
    --primary-color: #ff6b35;
    --secondary-color: #f7931e;
    /* ... */
}
```

### Adding More Restaurants
Edit the restaurant data arrays in:
- `index.php` (featured restaurants)
- `restaurant-listing.php` (all restaurants)
- `restaurant-detail.php` (menu items)

## License

This project is open source and available for educational purposes.

## Author

Built as a demonstration project for a food delivery app UI.

---

**Enjoy exploring the Food Delivery App! 🍕🍔🍜**

