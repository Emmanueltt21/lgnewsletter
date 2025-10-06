# LG Newsletter - Project Specifications

## 📋 Project Overview

**Project Name**: LG Newsletter - Lighthouse Global Missions Newsletter System  
**Version**: 1.2.0  
**Framework**: CodeIgniter 3.1.13  
**Language**: PHP 7.4+  
**Database**: MySQL 5.7+ / MariaDB 10.2+  
**License**: MIT License  

### Purpose
A comprehensive newsletter management system designed for Lighthouse Global Missions to manage email subscriptions, create and distribute newsletters, and provide administrative oversight of the entire newsletter ecosystem.

## 🏗️ System Architecture

### MVC Architecture (CodeIgniter 3)
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│     Models      │    │   Controllers   │    │     Views       │
│                 │    │                 │    │                 │
│ - Newsletter    │◄──►│ - Newsletter    │◄──►│ - Templates     │
│ - Subscriber    │    │ - Subscribers   │    │ - Forms         │
│ - User          │    │ - Dashboard     │    │ - Layouts       │
│ - Settings      │    │ - Admin_users   │    │ - Components    │
│ - Email_history │    │ - Settings      │    │                 │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

### Directory Structure
```
lgnewsletter/
├── application/
│   ├── controllers/          # Business logic controllers
│   │   ├── Newsletter.php    # Newsletter management
│   │   ├── Subscribers.php   # Subscriber operations
│   │   ├── Dashboard.php     # Admin dashboard
│   │   ├── Admin_users.php   # User management
│   │   ├── Settings.php      # System configuration
│   │   └── Api.php          # RESTful API endpoints
│   ├── models/              # Data access layer
│   │   ├── Newsletter_model.php
│   │   ├── User_model.php
│   │   ├── Settings_model.php
│   │   └── Fcm_model.php
│   ├── views/               # Presentation layer
│   │   ├── templates/       # Reusable templates
│   │   ├── newsletter/      # Newsletter views
│   │   ├── subscribers/     # Subscriber management
│   │   └── admin/          # Admin panel views
│   ├── config/             # Configuration files
│   │   ├── database.php    # Database configuration
│   │   ├── routes.php      # URL routing
│   │   └── config.php      # Application settings
│   ├── libraries/          # Custom libraries
│   │   └── BaseController.php
│   └── core/               # Core extensions
│       └── MY_Loader.php   # Custom loader
├── assets/                 # Static resources
│   ├── css/               # Stylesheets
│   ├── js/                # JavaScript files
│   ├── images/            # Image assets
│   └── uploads/           # User uploads
├── system/                # CodeIgniter framework
└── database_schema.sql    # Database structure
```

## 🗄️ Database Design

### Core Tables

#### 1. newsletter_subscribers
```sql
CREATE TABLE newsletter_subscribers (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    status ENUM('pending','confirmed','unsubscribed') DEFAULT 'pending',
    confirmation_token VARCHAR(255),
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    confirmed_at TIMESTAMP NULL,
    unsubscribed_at TIMESTAMP NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    INDEX idx_email (email),
    INDEX idx_status (status),
    INDEX idx_subscribed_at (subscribed_at)
);
```

#### 2. newsletters
```sql
CREATE TABLE newsletters (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    subject VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    sender_name VARCHAR(100) NOT NULL,
    sender_email VARCHAR(255) NOT NULL,
    status ENUM('draft','sent','scheduled') DEFAULT 'draft',
    recipients_count INT(11) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    sent_at TIMESTAMP NULL,
    scheduled_at TIMESTAMP NULL,
    created_by INT(11),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);
```

#### 3. admin_users
```sql
CREATE TABLE admin_users (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    role ENUM('admin','super_admin') DEFAULT 'admin',
    status ENUM('active','inactive') DEFAULT 'active',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_status (status)
);
```

#### 4. email_history
```sql
CREATE TABLE email_history (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    newsletter_id INT(11),
    subscriber_id INT(11),
    email_address VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    status ENUM('sent','failed','bounced') DEFAULT 'sent',
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    error_message TEXT,
    INDEX idx_newsletter_id (newsletter_id),
    INDEX idx_subscriber_id (subscriber_id),
    INDEX idx_status (status),
    INDEX idx_sent_at (sent_at)
);
```

### Extended Tables (Multi-purpose System)
- `tbl_users` - General user management
- `tbl_news` - News and announcements
- `tbl_devotionals` - Daily devotional content
- `tbl_events` - Event management
- `tbl_donations` - Donation tracking
- `tbl_media` - Media file management
- `settings` - System configuration

## 🔧 Technical Specifications

### Backend Technologies
- **Framework**: CodeIgniter 3.1.13
- **PHP Version**: 7.4+ (Compatible with PHP 8.x)
- **Database**: MySQL 5.7+ / MariaDB 10.2+
- **Web Server**: Apache 2.4+ / Nginx 1.18+
- **Email**: PHP Mail / SMTP / SendGrid integration

### Frontend Technologies
- **CSS Framework**: Bootstrap 4.6
- **JavaScript**: jQuery 3.6+
- **Rich Text Editor**: TinyMCE 5.x
- **Icons**: Font Awesome 5.x
- **Charts**: Chart.js (for dashboard analytics)

### Third-Party Integrations
- **Firebase Cloud Messaging (FCM)**: Push notifications
- **Stripe API**: Payment processing for donations
- **Email Services**: SMTP, SendGrid, Mailgun support
- **Image Processing**: GD Library / ImageMagick

## 🔐 Security Implementation

### Authentication & Authorization
```php
// BaseController security check
public function isLoggedIn() {
    $this->load->library('session');
    $isLoggedIn = $this->session->userdata('isLoggedIn');
    
    if (!isset($isLoggedIn) || $isLoggedIn != TRUE) {
        redirect(base_url().'login');
    }
}
```

### Security Features
1. **Password Security**: bcrypt hashing with salt
2. **SQL Injection Prevention**: CodeIgniter Active Record
3. **XSS Protection**: Input filtering and output encoding
4. **CSRF Protection**: Token-based form validation
5. **Session Security**: Secure session configuration
6. **Input Validation**: Server-side validation for all inputs

### Data Protection
- Email addresses encrypted in database
- Secure password reset mechanisms
- Audit trails for admin actions
- GDPR compliance features (data export/deletion)

## 📡 API Specifications

### RESTful API Endpoints

#### Authentication
```
POST /api/login
Content-Type: application/json
{
    "username": "admin",
    "password": "password"
}

Response:
{
    "status": "success",
    "token": "jwt_token_here",
    "user": {
        "id": 1,
        "username": "admin",
        "role": "super_admin"
    }
}
```

#### Newsletter Management
```
GET /api/newsletters              # List all newsletters
POST /api/newsletters             # Create new newsletter
GET /api/newsletters/{id}         # Get specific newsletter
PUT /api/newsletters/{id}         # Update newsletter
DELETE /api/newsletters/{id}      # Delete newsletter
POST /api/newsletters/{id}/send   # Send newsletter
```

#### Subscriber Management
```
GET /api/subscribers              # List subscribers
POST /api/subscribers             # Add subscriber
GET /api/subscribers/{id}         # Get subscriber details
PUT /api/subscribers/{id}         # Update subscriber
DELETE /api/subscribers/{id}      # Remove subscriber
POST /api/subscribe               # Public subscription
POST /api/unsubscribe            # Public unsubscription
```

### Response Format
```json
{
    "status": "success|error",
    "message": "Human readable message",
    "data": {
        // Response data object
    },
    "pagination": {
        "current_page": 1,
        "total_pages": 10,
        "total_records": 100
    }
}
```

## 🎨 User Interface Specifications

### Admin Dashboard Layout
```
┌─────────────────────────────────────────────────────────┐
│ Header Navigation                                        │
├─────────────┬───────────────────────────────────────────┤
│ Sidebar     │ Main Content Area                         │
│ - Dashboard │ ┌─────────────────────────────────────┐   │
│ - Newsletter│ │ Dashboard Widgets                   │   │
│ - Subscriber│ │ - Total Subscribers                 │   │
│ - Settings  │ │ - Recent Newsletters                │   │
│ - Users     │ │ - Email Statistics                  │   │
│             │ └─────────────────────────────────────┘   │
└─────────────┴───────────────────────────────────────────┘
```

### Responsive Design
- **Desktop**: Full sidebar navigation
- **Tablet**: Collapsible sidebar
- **Mobile**: Bottom navigation bar

### Color Scheme
- **Primary**: #007bff (Bootstrap Blue)
- **Secondary**: #6c757d (Bootstrap Gray)
- **Success**: #28a745 (Bootstrap Green)
- **Warning**: #ffc107 (Bootstrap Yellow)
- **Danger**: #dc3545 (Bootstrap Red)

## 🔄 System Workflows

### Newsletter Creation Workflow
```
1. Admin Login → 2. Create Newsletter → 3. Compose Content → 
4. Preview → 5. Select Recipients → 6. Send/Schedule → 7. Track Results
```

### Subscription Workflow
```
1. User Visits Site → 2. Fills Subscription Form → 3. Email Confirmation → 
4. User Clicks Confirm → 5. Status Updated to 'Confirmed' → 6. Welcome Email
```

### Email Sending Process
```php
// Simplified email sending workflow
public function send_newsletter($newsletter_id) {
    $newsletter = $this->newsletter_model->get_newsletter($newsletter_id);
    $subscribers = $this->newsletter_model->get_confirmed_subscribers();
    
    foreach ($subscribers as $subscriber) {
        $this->email->to($subscriber->email);
        $this->email->subject($newsletter->subject);
        $this->email->message($newsletter->content);
        
        if ($this->email->send()) {
            $this->log_email_success($newsletter_id, $subscriber->id);
        } else {
            $this->log_email_failure($newsletter_id, $subscriber->id);
        }
    }
}
```

## 📊 Performance Specifications

### System Requirements
- **Minimum RAM**: 512MB
- **Recommended RAM**: 2GB+
- **Storage**: 1GB minimum, 10GB recommended
- **Concurrent Users**: 100+ (with proper server configuration)

### Performance Optimizations
1. **Database Indexing**: Optimized queries with proper indexes
2. **Caching**: CodeIgniter caching for frequently accessed data
3. **Image Optimization**: Compressed images and lazy loading
4. **Minification**: CSS/JS minification for production
5. **CDN Integration**: Support for content delivery networks

### Scalability Considerations
- **Database**: Master-slave replication support
- **Load Balancing**: Multiple server deployment capability
- **Queue System**: Background job processing for email sending
- **Microservices**: API-first design for future service separation

## 🧪 Testing Specifications

### Testing Strategy
1. **Unit Testing**: PHPUnit for model and library testing
2. **Integration Testing**: API endpoint testing
3. **Functional Testing**: Selenium for UI testing
4. **Performance Testing**: Load testing with Apache Bench

### Test Coverage Areas
- User authentication and authorization
- Newsletter creation and sending
- Subscriber management operations
- API endpoint functionality
- Email delivery and tracking
- Database operations and data integrity

## 🚀 Deployment Specifications

### Production Environment
```yaml
Server Configuration:
  OS: Ubuntu 20.04 LTS / CentOS 8
  Web Server: Apache 2.4+ / Nginx 1.18+
  PHP: 7.4+ with required extensions
  Database: MySQL 8.0+ / MariaDB 10.5+
  SSL: Let's Encrypt / Commercial certificate
  
Performance:
  Memory: 4GB+ RAM
  Storage: SSD recommended
  Bandwidth: 100Mbps+
```

### Environment Configuration
```php
// Production settings
define('ENVIRONMENT', 'production');
$config['log_threshold'] = 1; // Error logging only
$config['sess_cookie_secure'] = TRUE; // HTTPS only
$config['csrf_protection'] = TRUE;
$config['global_xss_filtering'] = TRUE;
```

## 📈 Monitoring & Analytics

### System Monitoring
- **Error Logging**: Comprehensive error tracking
- **Performance Metrics**: Response time monitoring
- **Email Delivery**: Bounce and delivery rate tracking
- **User Analytics**: Dashboard usage statistics

### Key Performance Indicators (KPIs)
- Newsletter open rates
- Click-through rates
- Subscriber growth rate
- Email delivery success rate
- System uptime and performance

## 🔮 Future Enhancements

### Planned Features
1. **Advanced Analytics**: Detailed email campaign analytics
2. **A/B Testing**: Newsletter content testing capabilities
3. **Automation**: Drip campaigns and automated sequences
4. **Segmentation**: Advanced subscriber segmentation
5. **Templates**: Pre-built newsletter templates
6. **Integration**: CRM and marketing tool integrations

### Technical Improvements
- Migration to CodeIgniter 4
- Implementation of modern PHP features
- Enhanced API with GraphQL support
- Real-time notifications with WebSockets
- Advanced caching with Redis

---

**Document Version**: 1.0  
**Last Updated**: January 2025  
**Maintained By**: Lighthouse Global Missions Development Team