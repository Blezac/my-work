-- Create Database
CREATE DATABASE IF NOT EXISTS wendygee CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE wendygee;

-- Create Bookings Table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Booking Details
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    phone VARCHAR(20) NOT NULL,
    venue VARCHAR(255) NOT NULL,
    service_type VARCHAR(100) NOT NULL,
    
    -- Groom Information
    groom_firstname VARCHAR(100) NOT NULL,
    groom_lastname VARCHAR(100) NOT NULL,
    groom_oriki VARCHAR(150),
    groom_religion VARCHAR(100),
    groom_denomination VARCHAR(100),
    groom_musician VARCHAR(150),
    
    -- Groom's Father Information
    groom_father_name VARCHAR(200),
    groom_father_status ENUM('alive', 'deceased') DEFAULT 'alive',
    groom_father_religion VARCHAR(100),
    groom_father_denomination VARCHAR(100),
    groom_father_hometown VARCHAR(150),
    groom_father_compound VARCHAR(150),
    
    -- Groom's Mother Information
    groom_mother_name VARCHAR(200),
    groom_mother_status ENUM('alive', 'deceased') DEFAULT 'alive',
    groom_mother_religion VARCHAR(100),
    groom_mother_denomination VARCHAR(100),
    groom_mother_hometown VARCHAR(150),
    groom_mother_compound VARCHAR(150),
    groom_mother_colour VARCHAR(50),
    
    -- Bride Information
    bride_firstname VARCHAR(100) NOT NULL,
    bride_lastname VARCHAR(100) NOT NULL,
    bride_oriki VARCHAR(150),
    bride_religion VARCHAR(100),
    bride_denomination VARCHAR(100),
    bride_musician VARCHAR(150),
    
    -- Bride's Father Information
    bride_father_name VARCHAR(200),
    bride_father_status ENUM('alive', 'deceased') DEFAULT 'alive',
    bride_father_religion VARCHAR(100),
    bride_father_denomination VARCHAR(100),
    bride_father_hometown VARCHAR(150),
    bride_father_compound VARCHAR(150),
    
    -- Bride's Mother Information
    bride_mother_name VARCHAR(200),
    bride_mother_status ENUM('alive', 'deceased') DEFAULT 'alive',
    bride_mother_religion VARCHAR(100),
    bride_mother_denomination VARCHAR(100),
    bride_mother_hometown VARCHAR(150),
    bride_mother_compound VARCHAR(150),
    bride_mother_colour VARCHAR(50),
    
    -- Additional Information
    social_media_permission ENUM('yes', 'no') DEFAULT 'no',
    payment_status ENUM('pending', 'partial', 'paid') DEFAULT 'pending',
    booking_status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Indexes for faster queries
    INDEX idx_booking_date (booking_date),
    INDEX idx_phone (phone),
    INDEX idx_service_type (service_type),
    INDEX idx_booking_status (booking_status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Create Training Enrollments Table
CREATE TABLE IF NOT EXISTS training_enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(200) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    training_type ENUM('beginner', 'masterclass') NOT NULL,
    training_price DECIMAL(10, 2) NOT NULL,
    payment_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    enrollment_status ENUM('pending', 'active', 'completed', 'cancelled') DEFAULT 'pending',
    payment_receipt VARCHAR(255),
    transaction_reference VARCHAR(100),
    start_date DATE,
    end_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_email (email),
    INDEX idx_phone (phone),
    INDEX idx_training_type (training_type),
    INDEX idx_enrollment_status (enrollment_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Create Consultations Table
CREATE TABLE IF NOT EXISTS consultations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(200) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    preferred_date DATE NOT NULL,
    consultation_type ENUM('couple', 'family', 'wedding_planning', 'training_inquiry', 'general') NOT NULL,
    message TEXT NOT NULL,
    status ENUM('pending', 'scheduled', 'completed', 'cancelled') DEFAULT 'pending',
    admin_response TEXT,
    scheduled_date DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_status (status),
    INDEX idx_preferred_date (preferred_date),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Create Enquiries Table
CREATE TABLE IF NOT EXISTS enquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(200) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    admin_response TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Create Admin Users Table (for future admin panel)
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    full_name VARCHAR(200) NOT NULL,
    role ENUM('admin', 'staff') DEFAULT 'staff',
    status ENUM('active', 'inactive') DEFAULT 'active',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Insert default admin user (password: admin123 - CHANGE THIS!)
INSERT INTO admin_users (username, password, email, full_name, role, status) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@wendygeealaga.com', 'Administrator', 'admin', 'active');


-- Create Service Prices Table
CREATE TABLE IF NOT EXISTS service_prices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(150) NOT NULL,
    service_type VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_service_type (service_type),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Insert default service prices
INSERT INTO service_prices (service_name, service_type, price, description, status) VALUES
('Alaga Iduro', 'alaga_iduro', 0.00, 'Standing Compere Service', 'active'),
('Alaga Ijoko', 'alaga_ijoko', 0.00, 'Sitting Compere Service', 'active'),
('Both Alaga Services', 'both_alaga', 0.00, 'Complete Alaga Package', 'active'),
('Eru Iyawo Wrapping', 'eru_wrapping', 0.00, 'Engagement Items Wrapping', 'active'),
('Proposal & Acceptance Letter', 'proposal_letter', 0.00, 'Traditional Letters', 'active'),
('Complete Package', 'complete_package', 0.00, 'All Services Combined', 'active'),
('Training - Beginners', 'training_beginner', 150000.00, '4 Weeks Beginner Training', 'active'),
('Training - Masterclass', 'training_master', 300000.00, '8 Weeks Advanced Training', 'active');


-- Create Payment Transactions Table
CREATE TABLE IF NOT EXISTS payment_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT,
    transaction_reference VARCHAR(100) UNIQUE NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(50),
    payment_status ENUM('pending', 'successful', 'failed') DEFAULT 'pending',
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL,
    INDEX idx_transaction_ref (transaction_reference),
    INDEX idx_booking_id (booking_id),
    INDEX idx_payment_status (payment_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Create Site Settings Table
CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type VARCHAR(50) DEFAULT 'text',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_setting_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Insert default site settings
INSERT INTO site_settings (setting_key, setting_value, setting_type) VALUES
('site_name', 'WendyGee Alaga', 'text'),
('site_phone', '08142953849', 'text'),
('site_email', 'info@wendygeealaga.com', 'email'),
('site_address', 'Team6 House29 6th avenue Gwarinpa Abuja', 'text'),
('facebook_page', 'wendygee Alaga', 'text'),
('instagram_handle', 'WENDYGEEALAGAINABUJA', 'text'),
('tiktok_handle', 'wendygeealaga1', 'text'),
('commitment_percentage', '50', 'number'),
('booking_enabled', 'yes', 'boolean');


-- Create Views for easier data retrieval

-- View for upcoming bookings
CREATE OR REPLACE VIEW upcoming_bookings AS
SELECT 
    id,
    CONCAT(groom_firstname, ' ', groom_lastname) AS groom_name,
    CONCAT(bride_firstname, ' ', bride_lastname) AS bride_name,
    booking_date,
    booking_time,
    venue,
    service_type,
    phone,
    booking_status,
    payment_status
FROM bookings
WHERE booking_date >= CURDATE() AND booking_status != 'cancelled'
ORDER BY booking_date ASC, booking_time ASC;


-- View for monthly booking statistics
CREATE OR REPLACE VIEW monthly_booking_stats AS
SELECT 
    DATE_FORMAT(booking_date, '%Y-%m') AS month,
    COUNT(*) AS total_bookings,
    SUM(CASE WHEN booking_status = 'confirmed' THEN 1 ELSE 0 END) AS confirmed_bookings,
    SUM(CASE WHEN booking_status = 'completed' THEN 1 ELSE 0 END) AS completed_bookings,
    SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) AS paid_bookings
FROM bookings
GROUP BY DATE_FORMAT(booking_date, '%Y-%m')
ORDER BY month DESC;


-- Stored Procedure to get booking details
DELIMITER //
CREATE PROCEDURE GetBookingDetails(IN booking_id INT)
BEGIN
    SELECT * FROM bookings WHERE id = booking_id;
END //
DELIMITER ;


-- Stored Procedure to update booking status
DELIMITER //
CREATE PROCEDURE UpdateBookingStatus(
    IN booking_id INT,
    IN new_status VARCHAR(20)
)
BEGIN
    UPDATE bookings 
    SET booking_status = new_status,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = booking_id;
END //
DELIMITER ;


-- Grant necessary privileges (adjust as needed)
-- GRANT ALL PRIVILEGES ON wendygee.* TO 'your_user'@'localhost';
-- FLUSH PRIVILEGES;


-- Display success message
SELECT 'Database and tables created successfully!' AS Status;