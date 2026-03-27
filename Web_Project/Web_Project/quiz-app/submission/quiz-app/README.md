# QuizMaster Web App (Phase 3)

QuizMaster is a PHP and MySQL web application built for ICT 2204 / COM 2303 Phase 3. It includes user authentication, a contact form, and a database-backed dashboard for quiz attempts.

## Features

- User registration with password hashing (`password_hash`).
- User login/logout with PHP session handling.
- Contact form that stores messages in MySQL.
- Dashboard page that shows recent quiz attempts for the logged-in user.
- Input validation and sanitization in helper functions.
- Prepared SQL statements for safer database access.

## Project Structure

- `css/style.css` - Common styling
- `js/validation.js` - Client-side form validation
- `images/` - Static images (if needed)
- `includes/db.php` - PDO database connection
- `includes/functions.php` - Helper/session functions
- `auth/register.php` - Registration page and logic
- `auth/login.php` - Login page and logic
- `auth/logout.php` - Logout logic
- `index.php` - Home page
- `dashboard.php` - Protected user dashboard
- `contact.php` - Contact form page
- `database.sql` - MySQL schema export

## Setup (XAMPP/WAMP)

1. Copy this folder into your web root.
  - Example: `C:\xampp\htdocs\quiz-app`
2. Start Apache and MySQL in XAMPP/WAMP.
3. Open phpMyAdmin (`http://localhost/phpmyadmin`).
4. Create a database named `quiz_master`.
5. Import `database.sql` into `quiz_master`.
6. Check DB config in `includes/db.php` if your setup differs.
  - Host: `127.0.0.1`
  - Port: `3306`
  - DB: `quiz_master`
  - User: `root`
  - Password: empty by default
7. Open the app in browser:
  - `http://localhost/quiz-app/index.php`

## Login Instructions

1. Open `http://localhost/quiz-app/auth/register.php`.
2. Create an account (username, email, password).
3. Login from `http://localhost/quiz-app/auth/login.php`.
4. After login, you are redirected to `dashboard.php`.

## Notes for Evaluation

- Pages are navigable through the top navigation bar (Home, Dashboard, Contact).
- Database includes required `users` table and `messages` table, plus theme-specific `quiz_attempts` table.
- This submission is PHP + MySQL only and intended to run on XAMPP/WAMP.
