CREATE DATABASE ridesharepro;
USE ridesharepro;

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

CREATE TABLE vehicle_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_name VARCHAR(50) NOT NULL,
    min_price_fixed DECIMAL(10, 2) NOT NULL,
    max_price_fixed DECIMAL(10, 2) NOT NULL,
    min_price_per_km DECIMAL(10, 2) NOT NULL,
    max_price_per_km DECIMAL(10, 2) NOT NULL,
    commission_type ENUM('percentage', 'fixed') DEFAULT 'percentage',
    commission_value DECIMAL(10, 2) DEFAULT 0,
    commission_enabled BOOLEAN DEFAULT FALSE
);

CREATE TABLE rides (
    id INT AUTO_INCREMENT PRIMARY KEY,
    driver_id INT NOT NULL,
    pickup_location VARCHAR(255) NOT NULL,
    dropoff_location VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    seats_available INT NOT NULL,
    vehicle_type_id INT NOT NULL,
    pricing_model ENUM('fixed', 'per_km') NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    distance DECIMAL(10, 2) DEFAULT 0,
    status ENUM('available', 'fully booked', 'completed', 'cancelled') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (driver_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (vehicle_type_id) REFERENCES vehicle_types(id) ON DELETE CASCADE
);

CREATE TABLE ride_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ride_id INT NOT NULL,
    rider_id INT NOT NULL,
    seats_booked INT NOT NULL,
    total_fare DECIMAL(10, 2) NOT NULL,
    commission DECIMAL(10, 2) DEFAULT 0,
    payment_method ENUM('cash', 'jazzcash', 'easypaisa', 'coupon', 'card') NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ride_id) REFERENCES rides(id) ON DELETE CASCADE,
    FOREIGN KEY (rider_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE driver_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doc_name VARCHAR(50) UNIQUE NOT NULL,
    is_required BOOLEAN DEFAULT FALSE
);

CREATE TABLE driver_documents_uploaded (
    id INT AUTO_INCREMENT PRIMARY KEY,
    driver_id INT NOT NULL,
    doc_name VARCHAR(50) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    FOREIGN KEY (driver_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) UNIQUE NOT NULL,
    setting_value TEXT NOT NULL
);

INSERT INTO settings (setting_key, setting_value) VALUES
('pricing_model', 'both'),
('payment_methods', 'cash,jazzcash,easypaisa,coupon,card');

INSERT INTO driver_documents (doc_name, is_required) VALUES
('driving_license', 1),
('id_card', 0),
('profile_pic', 0);

INSERT INTO vehicle_types (type_name, min_price_fixed, max_price_fixed, min_price_per_km, max_price_per_km, commission_type, commission_value, commission_enabled) VALUES
('Sedan', 500, 1500, 20, 50, 'percentage', 10, 1),
('SUV', 800, 2000, 30, 70, 'fixed', 200, 1);

INSERT INTO users (name, email, password, role, verified) VALUES
('Admin User', 'admin@gmail.com', '$2y$10$V5eloLYK3sei.4ZKM0/2Ju0ExOwz4qsh2wWRs2B04gefPDUffjJpm', 'admin', TRUE);