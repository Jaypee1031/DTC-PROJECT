# DTC Project

This is the DTC (Digital Tracking and Control) PHP web application. It is used for managing users, attendance, and QR code-based tracking.

## Features
- User registration and login (with secure password hashing)
- Admin dashboard for managing users
- Attendance tracking via QR code
- User profile management
- Responsive design (improve as needed)

## Getting Started

### Requirements
- PHP 7.4 or higher
- MySQL
- Composer (for dependency management)
- Web server (e.g., Apache, XAMPP)

### Setup
1. Clone this repository:
   ```sh
   git clone https://github.com/Jaypee1031/DTC-PROJECT.git
   ```
2. Place the project folder in your web server root (e.g., `htdocs` for XAMPP).
3. Install dependencies:
   ```sh
   composer install
   ```
4. Set up your database:
   - Edit `db_connection.php` with your DB credentials.
   - Run `setup_database.php` to create the necessary tables.
5. Start your web server and access the app in your browser.

### Security Best Practices
- All passwords are hashed using `password_hash()`.
- User input is validated and sanitized.
- SQL queries use prepared statements to prevent SQL injection.

### Folder Structure
- `assets/` - Images and static files
- `uploads/` - Uploaded profile pictures
- `vendor/` - Composer dependencies
- `dtcc/` - (If present) Additional modules or dependencies

### Contribution
Pull requests are welcome! For major changes, please open an issue first to discuss what you would like to change.

### License
This project is for educational use. See individual files for license information.
