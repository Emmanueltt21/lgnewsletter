# LG Newsletter - Lighthouse Global Missions Newsletter System

A comprehensive newsletter management system built with CodeIgniter 3 for Lighthouse Global Missions. This application provides a complete solution for managing newsletter subscriptions, creating and sending newsletters, and administering user accounts.

## 🚀 Features

### Newsletter Management
- **Newsletter Creation**: Rich text editor with TinyMCE for creating professional newsletters
- **Email Campaign Management**: Send newsletters to confirmed subscribers
- **Newsletter History**: Track sent newsletters with detailed statistics
- **Draft Management**: Save and edit newsletter drafts before sending

### Subscriber Management
- **Subscription System**: Public subscription forms with email confirmation
- **Subscriber Dashboard**: View, search, and manage all subscribers
- **Status Management**: Handle pending, confirmed, and unsubscribed users
- **Bulk Operations**: Import/export subscriber lists

### Admin Panel
- **Dashboard**: Overview of key metrics and recent activity
- **User Management**: Admin user creation, editing, and role management
- **Settings Configuration**: System-wide settings and preferences
- **Email History**: Track all sent emails and delivery status

### Additional Features
- **Multi-media Support**: Audio, video, books, and devotional content management
- **Social Features**: Comments, replies, and user interactions
- **Event Management**: Create and manage church events
- **Donation System**: Handle online donations with payment integration
- **Mobile API**: RESTful API for mobile app integration
- **FCM Notifications**: Push notifications for mobile users

## 📋 Requirements

- **PHP**: 7.4 or higher
- **Web Server**: Apache/Nginx with mod_rewrite enabled
- **Database**: MySQL 5.7+ or MariaDB 10.2+
- **Extensions**: 
  - php-mysqli
  - php-curl
  - php-gd
  - php-mbstring
  - php-xml
  - php-zip

## 🛠️ Installation

### 1. Clone the Repository
```bash
git clone https://github.com/Emmanueltt21/lgnewsletter.git
cd lgnewsletter
```

### 2. Database Setup
1. Create a new MySQL database
2. Import the database schema:
```bash
mysql -u your_username -p your_database_name < database_schema.sql
```

### 3. Configuration
1. Copy and configure the database settings:
```php
// application/config/database.php
$db['default'] = array(
    'dsn'      => '',
    'hostname' => 'localhost',
    'username' => 'your_db_username',
    'password' => 'your_db_password',
    'database' => 'your_database_name',
    'dbdriver' => 'mysqli',
    // ... other settings
);
```

2. Update base URL in `application/config/config.php`:
```php
$config['base_url'] = 'http://your-domain.com/';
```

### 4. File Permissions
Set appropriate permissions for upload directories:
```bash
chmod 755 assets/uploads/
chmod 755 assets/images/
```

### 5. Web Server Configuration

#### Apache (.htaccess)
Ensure mod_rewrite is enabled and the .htaccess file is properly configured for clean URLs.

#### Nginx
Configure your server block to handle CodeIgniter routing:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

## 🚀 Usage

### Admin Access
1. Navigate to `/login` in your browser
2. Use default credentials:
   - **Username**: admin
   - **Password**: admin123
   - **⚠️ Change these credentials immediately after first login**

### Creating Newsletters
1. Go to **Newsletter** → **Create New**
2. Use the rich text editor to compose your content
3. Preview before sending
4. Send immediately or schedule for later

### Managing Subscribers
1. Access **Subscribers** from the main menu
2. View subscriber statistics and status
3. Export subscriber lists for external use
4. Handle unsubscribe requests

### System Settings
1. Navigate to **Settings**
2. Configure email settings (SMTP)
3. Set up FCM for push notifications
4. Customize application preferences

## 🔧 API Documentation

The application includes a RESTful API for mobile integration:

### Authentication
```
POST /api/login
Content-Type: application/json
{
    "email": "user@example.com",
    "password": "password"
}
```

### Newsletter Endpoints
- `GET /api/newsletters` - Get all newsletters
- `POST /api/subscribe` - Subscribe to newsletter
- `POST /api/unsubscribe` - Unsubscribe from newsletter

## 📱 Mobile Integration

The system supports mobile applications through:
- RESTful API endpoints
- FCM push notifications
- JSON responses for all data
- Image and media serving

## 🔒 Security Features

- **Password Hashing**: Secure password storage using PHP's password_hash()
- **SQL Injection Protection**: Prepared statements and CodeIgniter's Active Record
- **XSS Protection**: Input sanitization and output encoding
- **CSRF Protection**: Built-in CSRF tokens for forms
- **Session Management**: Secure session handling

## 🎨 Customization

### Themes and Styling
- CSS files located in `assets/css/`
- Bootstrap 4 framework included
- Responsive design for mobile compatibility

### Email Templates
- Newsletter templates in `application/views/newsletter/`
- Customizable HTML email layouts
- Support for embedded images and styling

## 📊 Database Schema

Key tables include:
- `newsletter_subscribers` - Subscriber information and status
- `newsletters` - Newsletter content and metadata
- `admin_users` - Administrative user accounts
- `email_history` - Tracking of sent emails
- `settings` - System configuration

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -am 'Add new feature'`)
4. Push to the branch (`git push origin feature/new-feature`)
5. Create a Pull Request

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🆘 Support

For support and questions:
- **Email**: support@lgmissions.org
- **Documentation**: Check the PROJECT_SPECIFICATIONS.md file
- **Issues**: Use GitHub Issues for bug reports

## 🔄 Version History

- **v1.0.0** - Initial release with core newsletter functionality
- **v1.1.0** - Added mobile API and push notifications
- **v1.2.0** - Enhanced admin panel and user management

---

**Lighthouse Global Missions** - Spreading the Gospel through digital communication