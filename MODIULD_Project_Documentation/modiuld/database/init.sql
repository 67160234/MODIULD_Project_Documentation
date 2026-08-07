-- MODIULD Database Schema
-- Version: 1.0

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Drop tables if they exist (for re-init)
DROP TABLE IF EXISTS token_blacklist;
DROP TABLE IF EXISTS workflow_steps;
DROP TABLE IF EXISTS widgets;
DROP TABLE IF EXISTS workflows;
DROP TABLE IF EXISTS dashboards;
DROP TABLE IF EXISTS modules;
DROP TABLE IF EXISTS loadouts;
DROP TABLE IF EXISTS workspaces;
DROP TABLE IF EXISTS users;

-- ============================================
-- USERS TABLE (implemented)
-- ============================================
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) DEFAULT NULL,
    avatar_url VARCHAR(500) DEFAULT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TOKEN BLACKLIST (for logout)
-- ============================================
CREATE TABLE token_blacklist (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    token_hash VARCHAR(255) NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_token_hash (token_hash),
    INDEX idx_expires_at (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- WORKSPACES TABLE (mock schema)
-- ============================================
CREATE TABLE workspaces (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT DEFAULT NULL,
    color VARCHAR(20) DEFAULT '#4F8EF7',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- LOADOUTS TABLE (mock schema)
-- ============================================
CREATE TABLE loadouts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    workspace_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT DEFAULT NULL,
    thumbnail_url VARCHAR(500) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- MODULES TABLE (mock schema)
-- ============================================
CREATE TABLE modules (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT DEFAULT NULL,
    category VARCHAR(50) DEFAULT NULL,
    icon VARCHAR(100) DEFAULT NULL,
    version VARCHAR(20) DEFAULT '1.0.0',
    author VARCHAR(100) DEFAULT NULL,
    is_published TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- DASHBOARDS TABLE (mock schema)
-- ============================================
CREATE TABLE dashboards (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    loadout_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    name VARCHAR(100) NOT NULL,
    layout JSON DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- SEED DATA
-- ============================================
-- Default admin user (password: Admin1234!)
INSERT INTO users (username, email, password_hash, full_name, role) VALUES
('admin', 'admin@modiuld.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'MODIULD Admin', 'admin');

-- Sample modules
INSERT INTO modules (name, description, category, icon) VALUES
('Room Booking', 'ระบบจองห้อง', 'Space and Resources', '🏠'),
('Asset Tracking', 'ติดตามทรัพย์สิน', 'Space and Resources', '📦'),
('Seat Allocation', 'จัดที่นั่ง', 'Space and Resources', '💺'),
('Profile Management', 'จัดการโปรไฟล์', 'Personnel and Teams', '👤'),
('Queueing', 'ระบบคิว', 'Personnel and Teams', '🔢'),
('Team Schedule', 'ตารางทีม', 'Personnel and Teams', '📅'),
('Work Tracking', 'ติดตามงาน', 'Personnel and Teams', '📊'),
('Inventory', 'คลังสินค้า', 'Operations', '🏭'),
('Report Builder', 'สร้างรายงาน', 'Operations', '📋');
