-- Newsletter System Tables Update
-- This file adds the newsletter subscription system tables to the existing database
-- Run this after importing the main database file

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` enum('pending','confirmed','unsubscribed') NOT NULL DEFAULT 'pending',
  `confirmation_token` varchar(255) DEFAULT NULL,
  `subscribed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `unsubscribed_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`),
  KEY `subscribed_at` (`subscribed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newsletters`
--

CREATE TABLE IF NOT EXISTS `newsletters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `sender_name` varchar(100) NOT NULL,
  `sender_email` varchar(255) NOT NULL,
  `status` enum('draft','sending','sent','failed') NOT NULL DEFAULT 'draft',
  `recipients_count` int(11) NOT NULL DEFAULT 0,
  `sent_count` int(11) NOT NULL DEFAULT 0,
  `failed_count` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `sent_at` timestamp NULL DEFAULT NULL,
  `scheduled_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `created_by` (`created_by`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_history`
--

CREATE TABLE IF NOT EXISTS `email_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipient_email` varchar(255) NOT NULL,
  `recipient_name` varchar(200) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `email_type` enum('confirmation','welcome','newsletter','unsubscribe') NOT NULL,
  `newsletter_id` int(11) DEFAULT NULL,
  `subscriber_id` int(11) DEFAULT NULL,
  `status` enum('sent','failed','pending') NOT NULL DEFAULT 'pending',
  `sent_at` timestamp NULL DEFAULT NULL,
  `error_message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `recipient_email` (`recipient_email`),
  KEY `email_type` (`email_type`),
  KEY `status` (`status`),
  KEY `newsletter_id` (`newsletter_id`),
  KEY `subscriber_id` (`subscriber_id`),
  KEY `created_at` (`created_at`),
  CONSTRAINT `fk_email_history_newsletter` FOREIGN KEY (`newsletter_id`) REFERENCES `newsletters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_email_history_subscriber` FOREIGN KEY (`subscriber_id`) REFERENCES `newsletter_subscribers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_settings`
--

CREATE TABLE IF NOT EXISTS `newsletter_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  `setting_description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('admin','editor') NOT NULL DEFAULT 'admin',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Insert default newsletter settings
--

INSERT INTO `newsletter_settings` (`setting_key`, `setting_value`, `setting_description`) VALUES
('smtp_host', 'smtp.gmail.com', 'SMTP server hostname'),
('smtp_port', '587', 'SMTP server port'),
('smtp_username', 'your-email@gmail.com', 'SMTP username'),
('smtp_password', 'your-app-password', 'SMTP password'),
('smtp_encryption', 'tls', 'SMTP encryption type (tls/ssl)'),
('sender_name', 'Lighthouse Global Missions', 'Default sender name'),
('sender_email', 'newsletter@lgmissions.org', 'Default sender email'),
('confirmation_subject', 'Please confirm your newsletter subscription', 'Subject for confirmation emails'),
('welcome_subject', 'Welcome to our newsletter!', 'Subject for welcome emails'),
('email_signature', 'Blessings,\nLighthouse Global Missions Team\n\nWebsite: https://lgmissions.org\nEmail: info@lgmissions.org', 'Email signature for all emails'),
('site_name', 'Lighthouse Global Missions', 'Site name for emails'),
('site_url', 'https://lgmissions.org', 'Site URL for emails'),
('unsubscribe_page', 'https://lgmissions.org/unsubscribe', 'Unsubscribe page URL'),
('privacy_policy', 'https://lgmissions.org/privacy', 'Privacy policy URL'),
('terms_of_service', 'https://lgmissions.org/terms', 'Terms of service URL');

-- --------------------------------------------------------

--
-- Insert default admin user
-- Username: admin
-- Password: admin123 (change this after first login)
--

INSERT INTO `admin_users` (`username`, `email`, `password`, `full_name`, `role`, `status`) VALUES
('admin', 'admin@lgmissions.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin', 'active');

-- --------------------------------------------------------

--
-- Add foreign key constraints for newsletters table
--

ALTER TABLE `newsletters`
ADD CONSTRAINT `fk_newsletters_admin` FOREIGN KEY (`created_by`) REFERENCES `admin_users` (`id`) ON DELETE RESTRICT;

-- --------------------------------------------------------

--
-- Create indexes for better performance
--

CREATE INDEX `idx_newsletter_subscribers_email_status` ON `newsletter_subscribers` (`email`, `status`);
CREATE INDEX `idx_email_history_type_status` ON `email_history` (`email_type`, `status`);
CREATE INDEX `idx_newsletters_status_created` ON `newsletters` (`status`, `created_at`);

-- --------------------------------------------------------

--
-- Create unsubscribe table for tracking unsubscribe reasons
--

CREATE TABLE IF NOT EXISTS `newsletter_unsubscribes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subscriber_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `reason` text DEFAULT NULL,
  `unsubscribed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscriber_id` (`subscriber_id`),
  KEY `email` (`email`),
  KEY `unsubscribed_at` (`unsubscribed_at`),
  CONSTRAINT `fk_unsubscribes_subscriber` FOREIGN KEY (`subscriber_id`) REFERENCES `newsletter_subscribers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Update existing settings table to include newsletter configuration
-- This will merge newsletter settings with existing app settings
--

INSERT IGNORE INTO `settings` (`id`, `fcm_server_key`, `mail_username`, `mail_password`, `mail_smtp_host`, `mail_protocol`, `mail_port`, `facebook_page`, `youtube_page`, `twitter_page`, `instagram_page`, `ads_interval`, `website_url`, `image_one`, `image_two`, `image_three`, `image_four`, `image_five`, `image_six`, `image_seven`, `image_eight`) VALUES
(101, 'fcm_server_key1', 'newsletter@lgmissions.org', 'your-app-password', 'smtp.gmail.com', 'smtp', 587, 'https://www.facebook.com/lgmissions', 'https://www.youtube.com/lgmissions', 'https://www.twitter.com/lgmissions', 'https://www.instagram.com/lgmissions', 30, 'https://lgmissions.org', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

COMMIT;