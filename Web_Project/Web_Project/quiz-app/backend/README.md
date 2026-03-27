# QuizMaster PHP Backend (Phase 3)

This backend implements the Phase 3 requirements using PHP and MySQL.

## Implemented Features

- MySQL integration via PDO in includes/db.php.
- User authentication with session handling:
  - auth/register.php
  - auth/login.php
  - auth/logout.php
- Contact form with message storage in contact.php.
- Theme-specific database table for quiz attempts (quiz_attempts).
- JSON API endpoints for frontend integration:
  - api/register.php
  - api/login.php
  - api/logout.php
  - api/session.php
  - api/contact.php
  - api/quiz-attempt.php
- SQL export file included as database.sql.

## Folder Structure

- includes/db.php: Database connection
- includes/functions.php: Helper and session functions
- auth/register.php: Registration
- auth/login.php: Login
- auth/logout.php: Logout
- contact.php: Contact form and message storage
- index.php: Entry page
- dashboard.php: Protected dashboard
- database.sql: Database schema

## Setup Instructions (XAMPP/WAMP)

1. Copy backend folder into your web root, for example htdocs/quiz-app/backend.
2. Start Apache and MySQL from XAMPP or WAMP.
3. Open phpMyAdmin and import database.sql.
4. Configure DB credentials if needed (supports environment variables):
  - DB_HOST (default: 127.0.0.1)
  - DB_PORT (default: 3307 in this repository)
  - DB_NAME (default: quiz_master)
  - DB_USER (default: root)
  - DB_PASS (default: empty)
5. Open in browser:
   - http://localhost/quiz-app/backend/index.php

## API Usage

- Base URL (local): http://127.0.0.1:8000/api
- Content-Type: application/json
- Session-based auth is cookie-based; frontend requests must include credentials.

Example endpoints:
- POST /register.php
- POST /login.php
- POST /logout.php
- GET /session.php
- POST /contact.php
- POST /quiz-attempt.php

## Notes

- Passwords are hashed using password_hash and validated using password_verify.
- Prepared statements are used to reduce SQL injection risk.
