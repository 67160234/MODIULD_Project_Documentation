-- MODIULD Database Schema MySQL 8.0
SET NAMES utf8mb4;
CREATE TABLE IF NOT EXISTS `users` (
    `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `email`         VARCHAR(255) NOT NULL,
    `username`      VARCHAR(50)  NOT NULL,
    `password_hash` VARCHAR(255) DEFAULT NULL,
    `google_sub`    VARCHAR(255) DEFAULT NULL,
    `display_name`  VARCHAR(100) NOT NULL DEFAULT '',
    `role`          VARCHAR(50)  NOT NULL DEFAULT 'user',
    `created_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_email` (`email`),
    UNIQUE KEY `uq_username` (`username`),
    UNIQUE KEY `uq_google_sub` (`google_sub`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `modules` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(100) NOT NULL,
    `category`    VARCHAR(100) NOT NULL,
    `description` TEXT,
    `icon`        VARCHAR(100) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `modules` (`name`, `category`, `description`, `icon`) VALUES
('Room Booking','Space and Resources','Manage room reservations.','calendar'),
('Asset Tracking','Space and Resources','Track organization assets.','package'),
('Seat Allocation','Space and Resources','Allocate seat assignments.','layout'),
('Profile Management','Personnel and Teams','Manage personnel profiles.','user'),
('Queueing','Personnel and Teams','Manage queues.','list'),
('Team Schedule','Personnel and Teams','Manage team schedules.','clock'),
('Work Tracking','Personnel and Teams','Track tasks and progress.','check-square');
CREATE TABLE IF NOT EXISTS `loadouts` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`     INT UNSIGNED DEFAULT NULL,
    `name`        VARCHAR(200) NOT NULL,
    `description` TEXT,
    `is_guest`    TINYINT(1) NOT NULL DEFAULT 0,
    `guest_token` VARCHAR(64) DEFAULT NULL,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_guest_token` (`guest_token`),
    CONSTRAINT `fk_loadout_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `loadout_modules` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `loadout_id` INT UNSIGNED NOT NULL,
    `module_id`  INT UNSIGNED NOT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_loadout_module` (`loadout_id`, `module_id`),
    CONSTRAINT `fk_lm_loadout` FOREIGN KEY (`loadout_id`) REFERENCES `loadouts`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_lm_module`  FOREIGN KEY (`module_id`)  REFERENCES `modules`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `rooms` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,`name` VARCHAR(100) NOT NULL,`capacity` INT NOT NULL DEFAULT 1,`location` VARCHAR(200),PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS `room_bookings` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,`room_id` INT UNSIGNED NOT NULL,`loadout_id` INT UNSIGNED NOT NULL,`title` VARCHAR(200) NOT NULL,`booked_by` VARCHAR(200),`start_time` DATETIME NOT NULL,`end_time` DATETIME NOT NULL,`status` ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,PRIMARY KEY (`id`),KEY `idx_room` (`room_id`),CONSTRAINT `fk_rb_room` FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO `rooms` (`name`,`capacity`,`location`) VALUES ('Meeting Room A',10,'Floor 1'),('Meeting Room B',6,'Floor 1'),('Conference Hall',50,'Floor 2'),('Board Room',20,'Floor 3'),('Training Room',30,'Floor 2');
CREATE TABLE IF NOT EXISTS `assets` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,`loadout_id` INT UNSIGNED NOT NULL,`asset_code` VARCHAR(50) NOT NULL,`name` VARCHAR(200) NOT NULL,`status` ENUM('available','in-use','maintenance','retired') NOT NULL DEFAULT 'available',`location` VARCHAR(200),`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,`updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,PRIMARY KEY (`id`),KEY `idx_loadout` (`loadout_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS `seats` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,`loadout_id` INT UNSIGNED NOT NULL,`seat_label` VARCHAR(50) NOT NULL,`assigned_to` VARCHAR(200) DEFAULT NULL,`status` ENUM('available','occupied') NOT NULL DEFAULT 'available',PRIMARY KEY (`id`),KEY `idx_loadout` (`loadout_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS `queue_items` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,`loadout_id` INT UNSIGNED NOT NULL,`queue_number` INT NOT NULL,`name` VARCHAR(200) NOT NULL,`status` ENUM('waiting','called','completed','cancelled') NOT NULL DEFAULT 'waiting',`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,`updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,PRIMARY KEY (`id`),KEY `idx_loadout` (`loadout_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS `schedule_items` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,`loadout_id` INT UNSIGNED NOT NULL,`title` VARCHAR(200) NOT NULL,`person` VARCHAR(200),`event_date` DATE NOT NULL,`start_time` TIME,`end_time` TIME,`description` TEXT,PRIMARY KEY (`id`),KEY `idx_loadout` (`loadout_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS `tasks` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,`loadout_id` INT UNSIGNED NOT NULL,`title` VARCHAR(200) NOT NULL,`assignee` VARCHAR(200),`status` ENUM('todo','in-progress','done') NOT NULL DEFAULT 'todo',`priority` ENUM('low','medium','high') NOT NULL DEFAULT 'medium',`progress` TINYINT UNSIGNED NOT NULL DEFAULT 0,`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,`updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,PRIMARY KEY (`id`),KEY `idx_loadout` (`loadout_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
