<?php
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechQuiz – Test Your Tech Knowledge</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #0ff;
            --accent: #ff2d78;
            --dark: #0a0a0f;
            --dark2: #12121a;
            --dark3: #1a1a28;
            --text: #e8e8f0;
            --muted: #888899;
            --card-bg: #16161f;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: var(--dark);
            color: var(--text);
            font-family: 'Syne', sans-serif;
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar {
            background: rgba(10,10,15,0.95);
            border-bottom: 1px solid rgba(0,255,255,0.1);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
        }

        .navbar-brand {
            font-family: 'Space Mono', monospace;
            font-size: 1.4rem;
            color: var(--primary) !important;
            letter-spacing: -1px;
        }

        .navbar-brand span { color: var(--accent); }

        .nav-link {
            color: var(--muted) !important;
            font-weight: 600;
            letter-spacing: 0.05em;
            transition: color 0.2s;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary) !important;
        }

        .nav-link.active {
            position: relative;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0; right: 0;
            height: 2px;
            background: var(--primary);
        }

        .btn-nav-login {
            background: transparent;
            border: 1px solid var(--primary);
            color: var(--primary) !important;
            border-radius: 4px;
            padding: 0.3rem 1rem;
            font-size: 0.85rem;
        }

        .btn-nav-login:hover {
            background: var(--primary);
            color: var(--dark) !important;
        }

        /* Hero */
        .hero {
            min-height: 90vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 5rem 0;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 60% 40%, rgba(0,255,255,0.07) 0%, transparent 60%),
                radial-gradient(ellipse 40% 40% at 80% 80%, rgba(255,45,120,0.07) 0%, transparent 50%);
        }

        .hero-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(0,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,255,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .hero-tag {
            display: inline-block;
            font-family: 'Space Mono', monospace;
            font-size: 0.75rem;
            color: var(--primary);
            border: 1px solid rgba(0,255,255,0.3);
            padding: 0.3rem 0.8rem;
            border-radius: 2px;
            letter-spacing: 0.15em;
            margin-bottom: 1.5rem;
        }

        .hero h1 {
            font-size: clamp(2.8rem, 6vw, 5rem);
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: -2px;
            margin-bottom: 1.5rem;
        }

        .hero h1 .highlight {
            color: var(--primary);
            position: relative;
        }

        .hero h1 .highlight2 { color: var(--accent); }

        .hero p {
            font-size: 1.1rem;
            color: var(--muted);
            max-width: 500px;
            line-height: 1.7;
            margin-bottom: 2.5rem;
        }

        .btn-primary-custom {
            background: var(--primary);
            color: var(--dark);
            border: none;
            padding: 0.85rem 2.5rem;
            font-family: 'Space Mono', monospace;
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            border-radius: 4px;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary-custom:hover {
            background: #fff;
            color: var(--dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0,255,255,0.3);
        }

        .btn-secondary-custom {
            background: transparent;
            color: var(--text);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 0.85rem 2rem;
            font-family: 'Space Mono', monospace;
            font-size: 0.9rem;
            border-radius: 4px;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary-custom:hover {
            border-color: var(--text);
            color: var(--text);
            transform: translateY(-2px);
        }

        .hero-stats {
            display: flex;
            gap: 2.5rem;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.07);
        }

        .stat-item .stat-num {
            font-family: 'Space Mono', monospace;
            font-size: 1.8rem;
            color: var(--primary);
            font-weight: 700;
        }

        .stat-item .stat-label {
            font-size: 0.8rem;
            color: var(--muted);
            letter-spacing: 0.1em;
        }

        /* Hero visual */
        .hero-visual {
            position: relative;
        }

        .quiz-preview-card {
            background: var(--card-bg);
            border: 1px solid rgba(0,255,255,0.15);
            border-radius: 12px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .quiz-preview-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }

        .qp-tag {
            font-family: 'Space Mono', monospace;
            font-size: 0.7rem;
            color: var(--muted);
            margin-bottom: 1rem;
        }

        .qp-question {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .qp-option {
            background: var(--dark3);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 6px;
            padding: 0.7rem 1rem;
            margin-bottom: 0.6rem;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .qp-option:hover { border-color: var(--primary); }
        .qp-option.correct { border-color: #00ff88; background: rgba(0,255,136,0.08); color: #00ff88; }
        .qp-option.wrong { border-color: var(--accent); background: rgba(255,45,120,0.08); color: var(--accent); }

        .qp-timer {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-family: 'Space Mono', monospace;
            font-size: 0.8rem;
            color: var(--primary);
            margin-bottom: 1.2rem;
        }

        /* Features Section */
        .features-section {
            padding: 5rem 0;
            background: var(--dark2);
        }

        .section-label {
            font-family: 'Space Mono', monospace;
            font-size: 0.75rem;
            color: var(--primary);
            letter-spacing: 0.2em;
            margin-bottom: 0.8rem;
        }

        .section-title {
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 1rem;
        }

        .feature-card {
            background: var(--card-bg);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 12px;
            padding: 2rem;
            height: 100%;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            transform: scaleX(0);
            transition: transform 0.3s;
        }

        .feature-card:hover {
            border-color: rgba(0,255,255,0.2);
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .feature-card:hover::before { transform: scaleX(1); }

        .feature-icon {
            width: 50px;
            height: 50px;
            background: rgba(0,255,255,0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: var(--primary);
            margin-bottom: 1.2rem;
        }

        .feature-card h5 {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.6rem;
        }

        .feature-card p {
            color: var(--muted);
            font-size: 0.9rem;
            line-height: 1.6;
            margin: 0;
        }

        /* Categories Section */
        .categories-section {
            padding: 5rem 0;
        }

        .category-card {
            background: var(--card-bg);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: block;
            color: var(--text);
        }

        .category-card:hover {
            border-color: var(--primary);
            color: var(--text);
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }

        .category-icon {
            font-size: 2.5rem;
            margin-bottom: 0.8rem;
            display: block;
        }

        .category-card h6 {
            font-weight: 700;
            margin-bottom: 0.3rem;
        }

        .category-card small {
            color: var(--muted);
            font-size: 0.8rem;
        }

        /* Footer */
        footer {
            background: var(--dark2);
            border-top: 1px solid rgba(255,255,255,0.07);
            padding: 2rem 0;
            text-align: center;
            color: var(--muted);
            font-size: 0.9rem;
        }

        footer a { color: var(--primary); text-decoration: none; }
        footer a:hover { text-decoration: underline; }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in { animation: fadeInUp 0.6s ease forwards; }
        .fade-in-1 { animation-delay: 0.1s; opacity: 0; }
        .fade-in-2 { animation-delay: 0.2s; opacity: 0; }
        .fade-in-3 { animation-delay: 0.3s; opacity: 0; }
        .fade-in-4 { animation-delay: 0.4s; opacity: 0; }

        .navbar-toggler { border-color: rgba(0,255,255,0.3); }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(0,255,255,0.8)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="bi bi-cpu me-1"></i>Tech<span>Quiz</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="quiz.php">Quiz</a></li>
                <li class="nav-item"><a class="nav-link" href="leaderboard.php">Leaderboard</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <span class="nav-link" style="color: var(--primary)!important;">
                            <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($_SESSION['username']) ?>
                        </span>
                    </li>
                    <li class="nav-item"><a class="btn-nav-login nav-link" href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="btn-nav-login nav-link" href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="hero-grid"></div>
    <div class="container position-relative">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="hero-tag fade-in fade-in-1">// TECH TRIVIA CHALLENGE</div>
                <h1 class="fade-in fade-in-2">
                    Test Your<br>
                    <span class="highlight">Tech</span><br>
                    <span class="highlight2">Knowledge</span>
                </h1>
                <p class="fade-in fade-in-3">
                    Challenge yourself with questions on web development, networking, programming, and cybersecurity. Compete, learn, and climb the leaderboard!
                </p>
                <div class="d-flex gap-3 flex-wrap fade-in fade-in-4">
                    <a href="<?= isset($_SESSION['user_id']) ? 'quiz.php' : 'login.php' ?>" class="btn-primary-custom">
                        <i class="bi bi-play-fill me-1"></i> Start Quiz
                    </a>
                    <a href="#how-to-play" class="btn-secondary-custom">How to Play</a>
                </div>
                <div class="hero-stats fade-in fade-in-4">
                    <div class="stat-item">
                        <div class="stat-num">40+</div>
                        <div class="stat-label">QUESTIONS</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-num">4</div>
                        <div class="stat-label">CATEGORIES</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-num">30s</div>
                        <div class="stat-label">PER QUESTION</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 hero-visual fade-in fade-in-3">
                <div class="quiz-preview-card">
                    <div class="qp-timer">
                        <i class="bi bi-clock"></i> TIME REMAINING: <span id="preview-timer">28s</span>
                    </div>
                    <div class="qp-tag">QUESTION 3 OF 10 &nbsp;|&nbsp; SCORE: 20 PTS</div>
                    <div class="qp-question">What does HTML stand for?</div>
                    <div class="qp-option correct"><i class="bi bi-check-circle me-2"></i>A) Hyper Text Markup Language</div>
                    <div class="qp-option wrong"><i class="bi bi-x-circle me-2"></i>B) High Tech Modern Language</div>
                    <div class="qp-option">C) Home Tool Markup Language</div>
                    <div class="qp-option">D) Hyperlinks and Text Markup Language</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How to Play -->
<section id="how-to-play" class="features-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label">HOW IT WORKS</div>
            <h2 class="section-title">Simple. Fast. Addictive.</h2>
            <p style="color: var(--muted); max-width: 500px; margin: 0 auto;">Three steps to prove your tech expertise and compete on the global leaderboard.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-person-plus"></i></div>
                    <h5>1. Create Account</h5>
                    <p>Register for free to track your scores, see your history, and compete on the leaderboard.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-grid-1x2"></i></div>
                    <h5>2. Pick a Category</h5>
                    <p>Choose from Web Development, Networking, Programming, or Cybersecurity. 10 random questions each time.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-trophy"></i></div>
                    <h5>3. Beat the Clock</h5>
                    <p>Answer each question within 30 seconds. Get instant feedback and see your final score.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="categories-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label">PICK YOUR BATTLEGROUND</div>
            <h2 class="section-title">Quiz Categories</h2>
        </div>
        <div class="row g-4">
            <div class="col-6 col-md-3">
                <a href="quiz.php?category=1" class="category-card">
                    <span class="category-icon">🌐</span>
                    <h6>Web Dev</h6>
                    <small>HTML, CSS, JS, PHP</small>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="quiz.php?category=2" class="category-card">
                    <span class="category-icon">🔗</span>
                    <h6>Networking</h6>
                    <small>TCP/IP, DNS, Protocols</small>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="quiz.php?category=3" class="category-card">
                    <span class="category-icon">💻</span>
                    <h6>Programming</h6>
                    <small>Algorithms, OOP, DSA</small>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="quiz.php?category=4" class="category-card">
                    <span class="category-icon">🔒</span>
                    <h6>Cybersecurity</h6>
                    <small>SSL, Threats, Encryption</small>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <p>© 2024 TechQuiz | All Rights Reserved | Contact: <a href="mailto:info@techquiz.com">info@techquiz.com</a></p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Animated preview timer
    let t = 28;
    setInterval(() => {
        t = t > 0 ? t - 1 : 30;
        const el = document.getElementById('preview-timer');
        if (el) el.textContent = t + 's';
    }, 1000);
</script>
</body>
</html>
