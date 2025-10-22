# Database and Admin Credentials Summary

## 🗄️ Database Connection Credentials

### Current Configuration
- **Host:** localhost
- **Database Name:** velvet_vogue
- **Username:** root
- **Password:** (empty)
- **Port:** 3306 (default)

### Configuration Files
1. **`includes/db_connect.php`** - Simple database connection
2. **`db_connect.php`** - Main database connection with table creation functions
3. **`includes/config.php`** - Complete configuration with site settings

### How to Update Database Credentials
1. **Manual Method:**
   - Edit each configuration file individually
   - Update host, database name, username, and password

2. **Automated Method:**
   - Use `update_database_config.php` script
   - Updates all configuration files at once
   - Tests connection automatically

## 👤 Admin User Credentials

### Current Admin Account
- **Username (Email):** admin@velvetvogue.com
- **Password:** admin123
- **Status:** Active
- **Permissions:** Full admin access

### Password Security
- **Hashing Algorithm:** bcrypt (PASSWORD_DEFAULT)
- **Storage:** Securely hashed in `users` table
- **Verification:** Uses `password_verify()` function

### Admin Account Management
- **Creation:** `setup_admin.php` creates/updates admin user
- **Authentication:** `admin_auth.php` handles login/logout
- **Session Management:** Secure session handling with timeout

## 🔧 Management Tools

### Available Scripts
1. **`database_credentials_guide.php`** - View current configuration and admin users
2. **`update_database_config.php`** - Update database connection settings
3. **`check_password_storage.php`** - Verify password hashing and storage
4. **`test_admin_login.php`** - Test admin authentication functionality
5. **`setup_admin.php`** - Create/update admin user account

### Quick Access Links
- Admin Login: `admin/login.php`
- Admin Dashboard: `admin/dashboard.php`
- Database Guide: `database_credentials_guide.php`
- Config Update: `update_database_config.php`

## 🔒 Security Features

### Database Security
- PDO with prepared statements
- Error handling and logging
- Connection timeout settings
- Charset specification (utf8mb4)

### Admin Security
- Password hashing with bcrypt
- Session management with timeout
- Admin activity logging
- CSRF protection (where implemented)
- Input validation and sanitization

### Recommendations
1. **Database User:** Create dedicated MySQL user instead of using 'root'
2. **Strong Password:** Use complex password for database connection
3. **Limited Permissions:** Grant only necessary database permissions
4. **Environment Variables:** Consider using .env file for sensitive data
5. **SSL/TLS:** Enable encrypted connections in production

## 📊 Database Structure

### Users Table
- Stores admin and customer accounts
- Password hashing for all users
- Admin flag (`is_admin`) for permission control
- Email verification system

### Session Management
- PHP sessions for authentication state
- Session timeout for security
- Automatic cleanup of old sessions

## 🚀 Getting Started

1. **Check Current Setup:**
   ```
   Visit: database_credentials_guide.php
   ```

2. **Update Database Credentials:**
   ```
   Visit: update_database_config.php
   ```

3. **Test Admin Login:**
   ```
   Visit: admin/login.php
   Username: admin@velvetvogue.com
   Password: admin123
   ```

4. **Verify Everything Works:**
   ```
   Visit: check_password_storage.php
   Visit: test_admin_login.php
   ```

## 📝 Notes

- All passwords are securely hashed before storage
- Database credentials are stored in plain text in config files (standard practice)
- Admin credentials are stored hashed in the database
- Multiple configuration files exist for different purposes
- Session management includes automatic timeout for security

---
*Last Updated: " . date('Y-m-d H:i:s') . "*