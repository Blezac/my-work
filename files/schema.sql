-- Greendome Engineering Ticketing System Database Schema
-- Production-ready MySQL schema

CREATE DATABASE IF NOT EXISTS greendome_tickets 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE greendome_tickets;

-- Staff users table
CREATE TABLE IF NOT EXISTS staff_users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    role ENUM('admin', 'support', 'manager') DEFAULT 'support',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Services/Categories table
CREATE TABLE IF NOT EXISTS services (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    service_slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_slug (service_slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tickets table
CREATE TABLE IF NOT EXISTS tickets (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_code VARCHAR(20) NOT NULL UNIQUE,
    service_id INT UNSIGNED NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_whatsapp VARCHAR(20) NOT NULL,
    customer_email VARCHAR(100),
    request_type ENUM('Inquiry', 'Support', 'Payment', 'Order', 'Training') NOT NULL,
    status ENUM('Open', 'In Progress', 'Waiting on Customer', 'Resolved', 'Closed') DEFAULT 'Open',
    priority ENUM('Low', 'Normal', 'High', 'Urgent') DEFAULT 'Normal',
    assigned_to INT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    resolved_at TIMESTAMP NULL,
    closed_at TIMESTAMP NULL,
    INDEX idx_ticket_code (ticket_code),
    INDEX idx_service (service_id),
    INDEX idx_status (status),
    INDEX idx_customer_whatsapp (customer_whatsapp),
    INDEX idx_assigned (assigned_to),
    INDEX idx_created (created_at),
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE RESTRICT,
    FOREIGN KEY (assigned_to) REFERENCES staff_users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ticket messages table
CREATE TABLE IF NOT EXISTS ticket_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT UNSIGNED NOT NULL,
    message_type ENUM('incoming', 'outgoing', 'system') NOT NULL,
    sender_type ENUM('customer', 'staff', 'system') NOT NULL,
    sender_id INT UNSIGNED NULL,
    message_text TEXT NOT NULL,
    whatsapp_message_id VARCHAR(100),
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ticket (ticket_id),
    INDEX idx_message_type (message_type),
    INDEX idx_created (created_at),
    INDEX idx_whatsapp_id (whatsapp_message_id),
    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES staff_users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ticket assignments history
CREATE TABLE IF NOT EXISTS ticket_assignments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT UNSIGNED NOT NULL,
    assigned_from INT UNSIGNED NULL,
    assigned_to INT UNSIGNED NULL,
    assigned_by INT UNSIGNED NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ticket (ticket_id),
    INDEX idx_assigned_to (assigned_to),
    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_from) REFERENCES staff_users(id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_to) REFERENCES staff_users(id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_by) REFERENCES staff_users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Status history table
CREATE TABLE IF NOT EXISTS status_history (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT UNSIGNED NOT NULL,
    old_status VARCHAR(50),
    new_status VARCHAR(50) NOT NULL,
    changed_by INT UNSIGNED NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ticket (ticket_id),
    INDEX idx_created (created_at),
    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES staff_users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- WhatsApp webhook logs (for debugging and audit)
CREATE TABLE IF NOT EXISTS whatsapp_webhook_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    webhook_data TEXT NOT NULL,
    processed TINYINT(1) DEFAULT 0,
    error_message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_processed (processed),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default services
INSERT INTO services (service_name, service_slug, description) VALUES
('Education', 'education', 'Online courses, certifications, and learning platform support'),
('IT Services', 'it-services', 'Custom web development, WordPress, Drupal, cloud storage and hosting'),
('CLACS-Farm', 'clacs-farm', 'Poultry orders, organic farm produce, delivery and supply'),
('Automobile', 'automobile', 'Remanufactured engines, installation support, technical diagnostics');

-- Insert default admin user (password: admin123 - CHANGE THIS!)
INSERT INTO staff_users (username, password_hash, full_name, email, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin@greendome.com', 'admin');

-- Create views for reporting
CREATE OR REPLACE VIEW ticket_overview AS
SELECT 
    t.id,
    t.ticket_code,
    s.service_name,
    t.customer_name,
    t.customer_whatsapp,
    t.request_type,
    t.status,
    t.priority,
    CONCAT(su.full_name) as assigned_to_name,
    t.created_at,
    t.updated_at,
    (SELECT COUNT(*) FROM ticket_messages WHERE ticket_id = t.id) as message_count,
    (SELECT COUNT(*) FROM ticket_messages WHERE ticket_id = t.id AND is_read = 0 AND message_type = 'incoming') as unread_count
FROM tickets t
LEFT JOIN services s ON t.service_id = s.id
LEFT JOIN staff_users su ON t.assigned_to = su.id
ORDER BY t.updated_at DESC;
