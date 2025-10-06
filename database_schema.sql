-- Newsletter Subscription System Database Schema
-- Lighthouse Global Missions

-- Create subscribers table
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `status` enum('pending','confirmed','unsubscribed') DEFAULT 'pending',
  `confirmation_token` varchar(255) DEFAULT NULL,
  `subscribed_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `unsubscribed_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`),
  KEY `idx_status` (`status`),
  KEY `idx_subscribed_at` (`subscribed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create newsletters table
CREATE TABLE IF NOT EXISTS `newsletters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `sender_name` varchar(100) NOT NULL,
  `sender_email` varchar(255) NOT NULL,
  `status` enum('draft','sent','scheduled') DEFAULT 'draft',
  `recipients_count` int(11) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `sent_at` timestamp NULL DEFAULT NULL,
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_sent_at` (`sent_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create email history table
CREATE TABLE IF NOT EXISTS `email_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipient_email` varchar(255) NOT NULL,
  `recipient_name` varchar(200) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `email_type` enum('confirmation','welcome','newsletter','system') NOT NULL,
  `newsletter_id` int(11) DEFAULT NULL,
  `subscriber_id` int(11) DEFAULT NULL,
  `status` enum('pending','sent','failed','bounced') DEFAULT 'pending',
  `sent_at` timestamp NULL DEFAULT NULL,
  `error_message` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_recipient_email` (`recipient_email`),
  KEY `idx_email_type` (`email_type`),
  KEY `idx_status` (`status`),
  KEY `idx_newsletter_id` (`newsletter_id`),
  KEY `idx_subscriber_id` (`subscriber_id`),
  KEY `idx_created_at` (`created_at`),
  FOREIGN KEY (`newsletter_id`) REFERENCES `newsletters`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`subscriber_id`) REFERENCES `newsletter_subscribers`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create newsletter settings table
CREATE TABLE IF NOT EXISTS `newsletter_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL UNIQUE,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('text','email','number','boolean','json') DEFAULT 'text',
  `description` varchar(255) DEFAULT NULL,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default settings
INSERT INTO `newsletter_settings` (`setting_key`, `setting_value`, `setting_type`, `description`) VALUES
('sender_name', 'Lighthouse Global Missions', 'text', 'Default sender name for emails'),
('sender_email', 'noreply@lgmissions.org', 'email', 'Default sender email address'),
('smtp_host', 'localhost', 'text', 'SMTP server hostname'),
('smtp_port', '587', 'number', 'SMTP server port'),
('smtp_username', '', 'text', 'SMTP username'),
('smtp_password', '', 'text', 'SMTP password'),
('smtp_encryption', 'tls', 'text', 'SMTP encryption type (tls/ssl)'),
('email_signature', 'Blessings,\nPastor Simon Mungwa\nLighthouse Global Missions', 'text', 'Email signature footer'),
('confirmation_subject', 'Confirm your subscription to Lighthouse Global Missions Newsletter', 'text', 'Subject for confirmation emails'),
('welcome_subject', 'Welcome to Lighthouse Global Missions!', 'text', 'Subject for welcome emails'),
('site_url', 'https://www.lgmissions.org', 'text', 'Website base URL');

-- Create admin users table (if not exists)
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `role` enum('admin','super_admin') DEFAULT 'admin',
  `status` enum('active','inactive') DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_username` (`username`),
  KEY `idx_email` (`email`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123 - should be changed in production)
INSERT INTO `admin_users` (`username`, `email`, `password`, `first_name`, `last_name`, `role`) VALUES
('admin', 'admin@lgmissions.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User', 'super_admin');