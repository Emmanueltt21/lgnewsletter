# Daily Light Max - API Documentation

This document provides comprehensive documentation for the Daily Light Max Church Management System API endpoints.

## Base URL
```
http://localhost:8080/
```

## Authentication
Most API endpoints require authentication. Include the user's email or authentication token in the request body where specified.

## Response Format
All API responses are in JSON format with the following structure:
```json
{
    "status": "ok|error",
    "message": "Response message (if applicable)",
    "data": "Response data (varies by endpoint)"
}
```

## Core API Endpoints

### 1. Discovery & Home Data
**Endpoint:** `GET /discover`
**Description:** Retrieves home page data including slider media, live streams, social media links, and settings.

**Request Body:**
```json
{
    "email": "user@example.com",
    "last_seen_inbox": 0
}
```

**Response:**
```json
{
    "status": "ok",
    "slider_media": [...],
    "livestream": [...],
    "facebook_page": "...",
    "youtube_page": "...",
    "twitter_page": "...",
    "instagram_page": "...",
    "ads_interval": 30,
    "inbox": 5,
    "website_url": "...",
    "image_one": "...",
    "events": 3,
    "radios": [...]
}
```

### 2. Devotionals
**Endpoint:** `GET /devotionals`
**Description:** Retrieves daily devotional content for a specific date.

**Request Body:**
```json
{
    "date": "2024-01-15"
}
```

**Response:**
```json
{
    "status": "ok",
    "devotional": {
        "id": 1,
        "title": "Daily Devotional Title",
        "content": "Devotional content...",
        "date": "2024-01-15",
        "author": "Author Name"
    }
}
```

### 3. News Management
**Endpoint:** `GET /getNews`
**Description:** Retrieves news articles for a specific date.

**Request Body:**
```json
{
    "date": "2024-01-15"
}
```

**Response:**
```json
{
    "status": "ok",
    "news": {
        "id": 1,
        "title": "News Title",
        "content": "News content...",
        "date": "2024-01-15",
        "author": "Author Name"
    }
}
```

### 4. Prayer Requests

#### Get Prayer Requests
**Endpoint:** `GET /getPrayer_request`
**Description:** Retrieves prayer requests with pagination support.

**Request Body:**
```json
{
    "page": 0,
    "email": "user@example.com"
}
```

**Response:**
```json
{
    "status": "ok",
    "prayer_requests": [
        {
            "id": 1,
            "subject": "Prayer Subject",
            "content": "Prayer request content...",
            "author": "User Name",
            "email": "user@example.com",
            "dou": "2024-01-15 10:30:00",
            "response": "Admin response (if any)"
        }
    ]
}
```

#### Add Prayer Request
**Endpoint:** `POST /addPrayer_request`
**Description:** Submits a new prayer request.

**Request Body:**
```json
{
    "subject": "Prayer Subject",
    "content": "Prayer request content...",
    "author": "User Name",
    "email": "user@example.com"
}
```

**Response:**
```json
{
    "status": "ok",
    "message": "Prayer request submitted successfully",
    "id": 123
}
```

### 5. Media Management

#### Fetch Categories Media
**Endpoint:** `GET /fetch_categories_media`
**Description:** Retrieves media content by category with pagination.

**Request Body:**
```json
{
    "category": "audio",
    "page": 0,
    "email": "user@example.com"
}
```

#### Update Media Views
**Endpoint:** `POST /update_media_total_views`
**Description:** Updates view count for media content.

**Request Body:**
```json
{
    "media_id": 123,
    "email": "user@example.com"
}
```

#### Like/Unlike Media
**Endpoint:** `POST /likeunlikemedia`
**Description:** Toggles like status for media content.

**Request Body:**
```json
{
    "media_id": 123,
    "email": "user@example.com"
}
```

### 6. Comments & Interactions

#### Make Comment
**Endpoint:** `POST /makecomment`
**Description:** Adds a comment to media content.

**Request Body:**
```json
{
    "media_id": 123,
    "comment": "Comment text...",
    "email": "user@example.com"
}
```

#### Load Comments
**Endpoint:** `GET /loadcomments`
**Description:** Retrieves comments for specific media.

**Request Body:**
```json
{
    "media_id": 123,
    "page": 0
}
```

### 7. Bible Management
**Endpoint:** `GET /get_bible`
**Description:** Retrieves available Bible versions and content.

**Response:**
```json
{
    "status": "ok",
    "bible_versions": [
        {
            "id": 1,
            "name": "King James Version",
            "source": "path/to/bible/file"
        }
    ]
}
```

### 8. Events Management
**Endpoint:** `GET /getEvents`
**Description:** Retrieves upcoming church events.

**Request Body:**
```json
{
    "date": "2024-01-15",
    "page": 0
}
```

### 9. Donations

#### Get PayPal Link
**Endpoint:** `GET /get_paypal_link`
**Description:** Generates PayPal payment link for donations.

**Request Body:**
```json
{
    "amount": 50.00,
    "currency": "USD",
    "description": "Church Donation"
}
```

### 10. FCM Token Management

#### Store FCM Token
**Endpoint:** `POST /storefcmtoken`
**Description:** Stores Firebase Cloud Messaging token for push notifications.

**Request Body:**
```json
{
    "email": "user@example.com",
    "fcm_token": "firebase_token_here",
    "device_type": "android"
}
```

#### Update FCM Token
**Endpoint:** `POST /updatefcmtoken`
**Description:** Updates existing FCM token.

**Request Body:**
```json
{
    "email": "user@example.com",
    "fcm_token": "new_firebase_token_here"
}
```

### 11. Search Functionality
**Endpoint:** `GET /search`
**Description:** Searches across all content types.

**Request Body:**
```json
{
    "query": "search term",
    "page": 0,
    "email": "user@example.com"
}
```

### 12. User Management

#### Fetch Android Users
**Endpoint:** `GET /fetchandroidusers`
**Description:** Retrieves list of mobile app users (admin only).

### 13. Social Features

#### Update User Profile
**Endpoint:** `POST /updateProfile`
**Description:** Updates user profile information.

**Request Body:**
```json
{
    "email": "user@example.com",
    "name": "New Name",
    "bio": "User bio...",
    "profile_image": "base64_image_data"
}
```

#### Follow/Unfollow User
**Endpoint:** `POST /follow_unfollow_user`
**Description:** Toggles follow status for another user.

**Request Body:**
```json
{
    "follower_email": "follower@example.com",
    "following_email": "following@example.com"
}
```

#### Make Post
**Endpoint:** `POST /make_post`
**Description:** Creates a new social media post.

**Request Body:**
```json
{
    "email": "user@example.com",
    "content": "Post content...",
    "image": "base64_image_data"
}
```

#### Fetch Posts
**Endpoint:** `GET /fetch_posts`
**Description:** Retrieves social media posts with pagination.

**Request Body:**
```json
{
    "page": 0,
    "email": "user@example.com"
}
```

## Admin Web Routes

### Prayer Request Management
- `GET /getPrayer_requestweb` - List all prayer requests (admin)
- `POST /updatePrayerRequest/{id}` - Update prayer request response
- `DELETE /deletePrayerRequest/{id}` - Delete prayer request

### Content Management
- `GET /adminNewsListing` - Admin news listing with DataTables
- `GET /adminPrayersListing` - Admin prayers listing
- `GET /adminDevotionalsListing` - Admin devotionals listing

### Donation Management
- `GET /donationslisting` - Admin donations listing

## Error Codes

| Code | Description |
|------|-------------|
| 200  | Success |
| 400  | Bad Request - Invalid parameters |
| 401  | Unauthorized - Authentication required |
| 404  | Not Found - Resource doesn't exist |
| 500  | Internal Server Error |

## Rate Limiting
- API requests are limited to prevent abuse
- Contact administrator for rate limit increases

## Data Formats

### Date Format
All dates should be in `YYYY-MM-DD` format.

### Image Upload
Images should be base64 encoded strings when uploading via API.

### Pagination
Most listing endpoints support pagination:
- `page`: Page number (0-based)
- Default page size: 20 items

## Security Notes

1. **Input Validation**: All inputs are sanitized and validated
2. **SQL Injection Protection**: Uses CodeIgniter's Active Record
3. **XSS Protection**: Output is escaped where necessary
4. **Authentication**: Required for most endpoints
5. **HTTPS**: Recommended for production environments

## Translation Support

The API supports content translation using DeepL API:
- Supported languages: Multiple (check DeepL documentation)
- Translation endpoint: Built into content retrieval endpoints
- Language parameter: `lang` (e.g., "es", "fr", "de")

## Mobile App Integration

The API is designed to support mobile applications with:
- Optimized JSON responses
- Image URL generation
- Push notification support via FCM
- Offline-friendly data structures

## Testing

Use tools like Postman or curl to test API endpoints:

```bash
curl -X POST http://localhost:8080/discover \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","last_seen_inbox":0}'
```

## Support

For API support or questions:
- Check the main README.md file
- Review the source code in `/application/controllers/Api.php`
- Contact the development team

---

**Note**: This API documentation is based on the current codebase analysis. Some endpoints may require additional configuration or database setup to function properly.