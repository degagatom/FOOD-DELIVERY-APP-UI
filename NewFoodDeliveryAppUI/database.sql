-- ============================================
-- Food Delivery App - Database Schema
-- MySQL Database Setup
-- ============================================

-- Create database
CREATE DATABASE IF NOT EXISTS food_delivery_db;
USE food_delivery_db;

-- ========== Users Table ==========
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========== Restaurants Table ==========
CREATE TABLE IF NOT EXISTS restaurants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    cuisine VARCHAR(100) NOT NULL,
    rating DECIMAL(3,1) DEFAULT 4.0,
    delivery_time VARCHAR(20) DEFAULT '30-40 min',
    icon VARCHAR(10) DEFAULT '🍽️',
    image_url VARCHAR(255) NULL,
    description TEXT NULL,
    is_featured BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_featured (is_featured),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========== Menu Items Table ==========
CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    restaurant_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT NULL,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(50) DEFAULT 'Popular',
    image_url VARCHAR(255) NULL,
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE,
    INDEX idx_restaurant (restaurant_id),
    INDEX idx_category (category),
    INDEX idx_available (is_available)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========== Orders Table ==========
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    order_number VARCHAR(20) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(50) NOT NULL,
    state VARCHAR(50) NOT NULL,
    zip_code VARCHAR(10) NOT NULL,
    instructions TEXT NULL,
    payment_method VARCHAR(20) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    delivery_fee DECIMAL(10,2) DEFAULT 2.99,
    tax DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_order_number (order_number),
    INDEX idx_status (status),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========== Order Items Table ==========
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    menu_item_id INT NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    item_price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    subtotal DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE CASCADE,
    INDEX idx_order (order_id),
    INDEX idx_menu_item (menu_item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========== Insert Sample Data ==========

-- Insert sample restaurants
INSERT INTO restaurants (name, cuisine, rating, delivery_time, icon, is_featured) VALUES
('Pizza Palace', 'Italian • Pizza', 4.5, '30-40 min', '🍕', TRUE),
('Burger King', 'American • Burgers', 4.3, '25-35 min', '🍔', TRUE),
('Sushi Express', 'Japanese • Sushi', 4.7, '35-45 min', '🍣', TRUE),
('Spice Garden', 'Indian • Curry', 4.6, '30-40 min', '🍛', TRUE),
('Dragon Wok', 'Chinese • Asian', 4.4, '25-35 min', '🥡', FALSE),
('Mario\'s Pizzeria', 'Italian • Pizza', 4.2, '20-30 min', '🍕', FALSE);

-- Insert sample menu items for Pizza Palace (restaurant_id = 1)
INSERT INTO menu_items (restaurant_id, name, description, price, category) VALUES
(1, 'Margherita Pizza', 'Fresh mozzarella, tomato sauce, basil', 12.99, 'Popular'),
(1, 'Pepperoni Pizza', 'Pepperoni, mozzarella, tomato sauce', 14.99, 'Popular'),
(1, 'Vegetarian Pizza', 'Mixed vegetables, mozzarella, tomato sauce', 13.99, 'Vegetarian'),
(1, 'BBQ Chicken Pizza', 'Grilled chicken, BBQ sauce, red onions', 16.99, 'Popular'),
(1, 'Hawaiian Pizza', 'Ham, pineapple, mozzarella, tomato sauce', 15.99, 'Popular');

-- Insert sample menu items for Burger King (restaurant_id = 2)
INSERT INTO menu_items (restaurant_id, name, description, price, category) VALUES
(2, 'Classic Burger', 'Beef patty, lettuce, tomato, special sauce', 8.99, 'Popular'),
(2, 'Cheese Burger', 'Beef patty, cheese, pickles, onions', 9.99, 'Popular'),
(2, 'Bacon Burger', 'Beef patty, bacon, cheese, BBQ sauce', 11.99, 'Popular'),
(2, 'Veggie Burger', 'Vegetable patty, lettuce, tomato, mayo', 7.99, 'Vegetarian'),
(2, 'Double Burger', 'Two beef patties, cheese, special sauce', 12.99, 'Popular');

-- Insert sample menu items for Sushi Express (restaurant_id = 3)
INSERT INTO menu_items (restaurant_id, name, description, price, category) VALUES
(3, 'Salmon Sushi Roll', 'Fresh salmon, rice, nori, avocado', 15.99, 'Popular'),
(3, 'Tuna Sashimi', 'Fresh tuna slices, wasabi, soy sauce', 18.99, 'Popular'),
(3, 'California Roll', 'Crab, avocado, cucumber, rice', 12.99, 'Popular'),
(3, 'Dragon Roll', 'Eel, avocado, cucumber, eel sauce', 16.99, 'Popular'),
(3, 'Rainbow Roll', 'Assorted fish, avocado, cucumber', 17.99, 'Popular');

-- Insert sample menu items for Spice Garden (restaurant_id = 4)
INSERT INTO menu_items (restaurant_id, name, description, price, category) VALUES
(4, 'Butter Chicken', 'Creamy tomato curry with tender chicken', 14.99, 'Popular'),
(4, 'Chicken Tikka Masala', 'Spiced chicken in rich tomato sauce', 15.99, 'Popular'),
(4, 'Vegetable Biryani', 'Fragrant rice with mixed vegetables', 12.99, 'Vegetarian'),
(4, 'Lamb Curry', 'Tender lamb in spicy curry sauce', 17.99, 'Popular'),
(4, 'Palak Paneer', 'Spinach curry with cottage cheese', 13.99, 'Vegetarian');

-- Insert sample menu items for Dragon Wok (restaurant_id = 5)
INSERT INTO menu_items (restaurant_id, name, description, price, category) VALUES
(5, 'Sweet and Sour Chicken', 'Crispy chicken in tangy sauce', 13.99, 'Popular'),
(5, 'Kung Pao Shrimp', 'Spicy shrimp with peanuts and vegetables', 16.99, 'Popular'),
(5, 'Beef Lo Mein', 'Stir-fried noodles with beef and vegetables', 14.99, 'Popular'),
(5, 'General Tso\'s Chicken', 'Crispy chicken in sweet and spicy sauce', 15.99, 'Popular'),
(5, 'Vegetable Fried Rice', 'Fried rice with mixed vegetables', 11.99, 'Vegetarian');

-- Insert sample menu items for Mario's Pizzeria (restaurant_id = 6)
INSERT INTO menu_items (restaurant_id, name, description, price, category) VALUES
(6, 'Neapolitan Pizza', 'Traditional Italian pizza with fresh ingredients', 13.99, 'Popular'),
(6, 'Quattro Formaggi', 'Four cheese pizza', 15.99, 'Popular'),
(6, 'Prosciutto Pizza', 'Prosciutto, arugula, parmesan', 16.99, 'Popular'),
(6, 'Margherita Classic', 'Classic margherita with fresh basil', 12.99, 'Popular'),
(6, 'Pepperoni Special', 'Double pepperoni with extra cheese', 14.99, 'Popular');

