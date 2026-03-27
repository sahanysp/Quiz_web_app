# 🖥️ TechQuiz — Interactive Tech Trivia Game

A full-stack web application built for **ICT 2204 – Web Technologies** that lets users test their technology knowledge through an interactive quiz with real-time scoring, timers, and a leaderboard.

---

## 📌 Project Info

| Detail | Info |
|---|---|
| Course | ICT 2204 – Web Technologies |
| Students | B B Y S Perera (ICT/2023/004), M R V Bandara (ICT/2023/014) |
| Theme | Quiz / Trivia Game |
| Tech Stack | HTML, CSS, Bootstrap 5, JavaScript, PHP, MySQL |

---

## 🚀 Features

- **User Registration & Login** — Secure accounts with password hashing
- **4 Quiz Categories** — Web Development, Networking, Programming, Cybersecurity
- **Dynamic Question Generation** — 10 random questions pulled from MySQL each round
- **30-Second Timer** — Countdown per question with visual warning
- **Instant Feedback** — Correct/wrong answer revealed after each submission
- **Real-Time Score Tracking** — Score updates live throughout the quiz
- **Results Page** — Final score, accuracy %, correct/wrong breakdown, and performance message
- **Leaderboard** — Top 20 scores filterable by category
- **Contact Form** — Client-side and server-side validated contact form
- **Responsive Design** — Works on desktop, tablet, and mobile via Bootstrap 5

---

## 📁 File Structure

```
techquiz/
│
├── index.php          # Home page — hero, features, category cards
├── login.php          # User login
├── register.php       # User registration
├── quiz.php           # Quiz page — questions, timer, options
├── result.php         # Results page — score, breakdown, feedback
├── leaderboard.php    # Leaderboard — top scores by category
├── contact.php        # Contact form with validation
├── save_score.php     # AJAX endpoint — saves score to DB
├── logout.php         # Destroys session and redirects
├── db.php             # Database connection config
└── db.sql             # MySQL schema + seed data (40 questions)
```

---

## 🛠️ Setup Instructions

### Option 1 — Local (XAMPP / WAMP / Laragon)

1. **Clone the repo**
   ```bash
   git clone https://github.com/your-username/techquiz.git
   ```

2. **Move files** into your server's root folder:
   - XAMPP → `C:/xampp/htdocs/techquiz/`
   - WAMP → `C:/wamp64/www/techquiz/`
   - Laragon → `C:/laragon/www/techquiz/`

3. **Import the database**
   - Open `http://localhost/phpmyadmin`
   - Create a new database named `techquiz`
   - Click **Import** → select `db.sql` → click **Go**

4. **Configure the connection** — open `db.php` and update:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');       // your MySQL username
   define('DB_PASS', '');           // your MySQL password
   define('DB_NAME', 'techquiz');
   ```

5. **Visit** `http://localhost/techquiz/`

---

### Option 2 — Free Online Hosting (InfinityFree)

1. Sign up at [infinityfree.com](https://infinityfree.com)
2. Create a hosting account and choose a free subdomain
3. Go to **Control Panel → MySQL Databases** → create a database and note down the host, username, password, and database name
4. Go to **Control Panel → phpMyAdmin** → select your database → **Import** → upload `db.sql`
5. Update `db.php` with the credentials from step 3
6. Go to **Control Panel → File Manager → htdocs** → upload all `.php` files
7. Visit your subdomain — the site is live!

---

## 🗄️ Database Schema

| Table | Description |
|---|---|
| `users` | Registered user accounts (id, username, email, password hash) |
| `categories` | Quiz categories (Web Dev, Networking, Programming, Cybersecurity) |
| `questions` | 40 multiple-choice questions with 4 options and correct answer |
| `scores` | User quiz results (score, total, category, timestamp) |
| `contacts` | Messages submitted via the contact form |

---

## 🎮 How to Play

1. Register or log in
2. Pick a category from the home page or quiz page
3. Answer each question within 30 seconds
4. Submit your answer to see instant feedback and the correct answer
5. Complete all 10 questions to view your results
6. Check the leaderboard to see how you rank!

---

## 🖼️ Pages Overview

| Page | URL | Description |
|---|---|---|
| Home | `/index.php` | Landing page with features and category selector |
| Login | `/login.php` | User login form |
| Register | `/register.php` | New account creation |
| Quiz | `/quiz.php` | Main quiz interface |
| Results | `/result.php` | Score and performance breakdown |
| Leaderboard | `/leaderboard.php` | Top scores, filterable by category |
| Contact | `/contact.php` | Feedback form with validation |

---

## ⚙️ Tech Stack

| Layer | Technology |
|---|---|
| Frontend | HTML5, CSS3, Bootstrap 5, Vanilla JavaScript |
| Backend | PHP 8+ |
| Database | MySQL |
| Icons | Bootstrap Icons |
| Fonts | Google Fonts (Syne, Space Mono) |

---

## 📋 Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- A web server (Apache via XAMPP/WAMP/Laragon, or any free PHP host)

---

## 📄 License

This project was created for academic purposes as part of the ICT 2204 Web Technologies course.
