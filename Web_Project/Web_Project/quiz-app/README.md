# QuizMaster - Final Project (Phases 1, 2, and 3)

This project contains:
- React frontend (Vite) for quiz UI and user-facing routes.
- PHP + MySQL backend for authentication, contact form storage, and quiz attempt persistence.

## Implemented Features

- Interactive quiz flow with score calculation.
- User registration, login, and logout with PHP sessions.
- Contact form with messages stored in MySQL.
- Theme-specific table for quiz attempts linked to users.
- Frontend-backend integration through JSON API endpoints.
- SQL export file for database submission.

## Project Structure

- src/: React frontend source
- backend/: PHP backend (Phase 3)
  - includes/db.php: PDO connection
  - includes/functions.php: shared helpers
  - includes/api.php: CORS + JSON API helpers
  - auth/: server-rendered auth pages
  - api/: API endpoints used by React frontend
  - contact.php, dashboard.php, index.php
  - database.sql

## Setup Steps

1. Start PHP backend

```powershell
Set-Location "d:\Web_Project\quiz-app"
php -S 127.0.0.1:8000 -t backend
```

2. Start MySQL (XAMPP or WAMP)
- If your default port 3306 is occupied, run XAMPP MySQL on 3307.

3. Import database
- Import backend/database.sql into MySQL.

4. Configure DB credentials if needed
- backend/includes/db.php supports environment variables:
  - DB_HOST (default: 127.0.0.1)
  - DB_PORT (default: 3307)
  - DB_NAME (default: quiz_master)
  - DB_USER (default: root)
  - DB_PASS (default: empty)

5. Start frontend

```powershell
Set-Location "d:\Web_Project\quiz-app"
npm install
npm run dev
```

Frontend URL:
- http://localhost:5173

Backend URL:
- http://127.0.0.1:8000

## Frontend Routes

- / (Home)
- /quiz
- /results
- /register
- /login
- /dashboard
- /contact

## API Endpoints (Backend)

Base URL:
- http://127.0.0.1:8000/api

Endpoints:
- POST /register.php
- POST /login.php
- POST /logout.php
- GET /session.php
- POST /contact.php
- POST /quiz-attempt.php

## Submission Checklist

- All project files pushed to GitHub.
- backend/database.sql included.
- README includes setup and run instructions.
