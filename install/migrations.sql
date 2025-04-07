CREATE DATABASE ridesharepro;
USE ridesharepro;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'driver', 'rider') NOT NULL,
    chattiness ENUM('quiet', 'chatty', 'neutral') DEFAULT 'neutral',
    verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Rides Table
CREATE TABLE rides (
    id INT AUTO_INCREMENT PRIMARY KEY,
    driver_id INT NOT NULL,
    pickup_location VARCHAR(255) NOT NULL,
    dropoff_location VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    seats_available INT NOT NULL,
    pricing_model ENUM('fixed', 'per_km') NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    distance DECIMAL(10, 2) DEFAULT 0,
    status ENUM('available', 'fully booked', 'completed', 'cancelled') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (driver_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Ride Bookings Table
CREATE TABLE ride_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ride_id INT NOT NULL,
    rider_id INT NOT NULL,
    seats_booked INT NOT NULL,
    total_fare DECIMAL(10, 2) NOT NULL,
    payment_method ENUM('cash', 'jazzcash', 'easypaisa', 'coupon', 'card') NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ride_id) REFERENCES rides(id) ON DELETE CASCADE,
    FOREIGN KEY (rider_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Messages Table
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Settings Table
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) UNIQUE NOT NULL,
    setting_value TEXT NOT NULL
);

-- Insert Default Settings
INSERT INTO settings (setting_key, setting_value) VALUES
('pricing_model', 'both'),
('payment_methods', 'cash,jazzcash,easypaisa,coupon,card');

-- Insert Sample Admin User
INSERT INTO users (name, email, password, role, verified) VALUES
('Admin User', 'admin@ridesharepro.com', '$2y$10$V5eloLYK3sei.4ZKM0/2Ju0ExOwz4qsh2wWRs2B04gefPDUffjJpm', 'admin', TRUE);