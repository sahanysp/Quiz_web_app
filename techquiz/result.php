<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$score   = (int)($_GET['score']   ?? 0);
$total   = (int)($_GET['total']   ?? 100);
$correct = (int)($_GET['correct'] ?? 0);
$wrong   = (int)($_GET['wrong']   ?? 0);
$cat_id  = (int)($_GET['cat']     ?? 1);

$percent = $total > 0 ? round(($score / $total) * 100) : 0;
$questions_total = $correct + $wrong;

// Performance message
if ($percent >= 90) {
    $msg = "🏆 Outstanding! You're a tech genius!";
    $msg_class = "success";
} elseif ($percent >= 70) {
    $msg = "🎉 Excellent work! You really know your stuff!";
    $msg_class = "success";
} elseif ($percent >= 50) {
    $msg = "👍 Good effort! Keep practicing to improve!";
    $msg_class = "warning";
} else {
    $msg = "📚 Keep studying! You'll do better next time!";
    $msg_class = "danger";
}

// Get category name
$cat_row = $conn->query("SELECT name FROM categories WHERE id = $cat_id")->fetch_assoc();
$cat_name = $cat_row ? $cat_row['name'] : 'Tech';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results – TechQuiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #0ff;
            --accent: #ff2d78;
            --success: #00ff88;
            --warning: #ffbb00;
            --dark: #0a0a0f;
            --dark2: #12121a;
            --dark3: #1a1a28;
            --text: #e8e8f0;
            --muted: #888899;
            --card-bg: #16161f;
        }
        body {
            background-color: var(--dark);
            color: var(--text);
            font-family: 'Syne', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background: rgba(10,10,15,0.95);
            border-bottom: 1px solid rgba(0,255,255,0.1);
            padding: 1rem 2rem;
        }
        .navbar-brand {
            font-family: 'Space Mono', monospace;
            font-size: 1.4rem;
            color: var(--primary) !important;
        }
        .navbar-brand span { color: var(--accent); }
        .nav-link { color: var(--muted) !important; font-weight: 600; }
        .nav-link:hover, .nav-link.active { color: var(--primary) !important; }

        .results-wrapper {
            flex: 1;
            padding: 3rem 1rem;
            position: relative;
        }
        .results-wrapper::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse 70% 50% at 50% 30%, rgba(0,255,255,0.05) 0%, transparent 60%);
        }

        /* Score Card */
        .score-card {
            background: var(--card-bg);
            border: 1px solid rgba(0,255,255,0.15);
            border-radius: 16px;
            padding: 2.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .score-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }
        .quiz-complete-tag {
            font-family: 'Space Mono', monospace;
            font-size: 0.75rem;
            color: var(--primary);
            letter-spacing: 0.2em;
            margin-bottom: 1rem;
        }
        .score-card h2 {
            font-weight: 800;
            font-size: 1.8rem;
            letter-spacing: -1px;
            margin-bottom: 1.5rem;
        }
        .score-circle {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: var(--dark3);
            border: 3px solid rgba(0,255,255,0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            position: relative;
        }
        .score-circle::before {
            content: '';
            position: absolute; inset: -8px;
            border-radius: 50%;
            background: conic-gradient(var(--primary) <?= $percent * 3.6 ?>deg, transparent 0deg);
            opacity: 0.3;
        }
        .score-num {
            font-family: 'Space Mono', monospace;
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
        }
        .score-denom {
            font-family: 'Space Mono', monospace;
            font-size: 0.9rem;
            color: var(--muted);
        }
        .score-percent {
            font-family: 'Space Mono', monospace;
            font-size: 0.8rem;
            color: var(--primary);
            margin-top: 0.3rem;
        }
        .feedback-msg {
            font-size: 1.05rem;
            font-weight: 600;
            margin-bottom: 0;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            display: inline-block;
        }
        .feedback-msg.success { background: rgba(0,255,136,0.1); color: var(--success); border: 1px solid rgba(0,255,136,0.2); }
        .feedback-msg.warning { background: rgba(255,187,0,0.1); color: var(--warning); border: 1px solid rgba(255,187,0,0.2); }
        .feedback-msg.danger { background: rgba(255,45,120,0.1); color: #ff6699; border: 1px solid rgba(255,45,120,0.2); }

        /* Performance Card */
        .perf-card {
            background: var(--card-bg);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 1.5rem;
        }
        .perf-card h5 {
            font-weight: 700;
            margin-bottom: 1.5rem;
            font-size: 1rem;
            letter-spacing: 0.05em;
        }
        .stat-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.9rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .stat-row:last-child { border-bottom: none; }
        .stat-label { color: var(--muted); font-size: 0.9rem; display: flex; align-items: center; gap: 0.6rem; }
        .stat-value { font-family: 'Space Mono', monospace; font-weight: 700; font-size: 1rem; }
        .stat-value.green { color: var(--success); }
        .stat-value.red { color: var(--accent); }
        .stat-value.blue { color: var(--primary); }
        .stat-value.yellow { color: var(--warning); }

        /* Action Buttons */
        .btn-result {
            font-family: 'Space Mono', monospace;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 0.85rem 2rem;
            border-radius: 6px;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-play-again {
            background: var(--primary);
            color: var(--dark);
            border: none;
        }
        .btn-play-again:hover {
            background: #fff;
            color: var(--dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,255,255,0.3);
        }
        .btn-leaderboard {
            background: transparent;
            color: var(--text);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .btn-leaderboard:hover {
            border-color: var(--primary);
            color: var(--primary);
        }
        .btn-home {
            background: transparent;
            color: var(--muted);
            border: 1px solid rgba(255,255,255,0.1);
        }
        .btn-home:hover { color: var(--text); border-color: rgba(255,255,255,0.2); }

        footer {
            background: var(--dark2);
            border-top: 1px solid rgba(255,255,255,0.07);
            padding: 1.5rem 0;
            text-align: center;
            color: var(--muted);
            font-size: 0.85rem;
        }
        footer a { color: var(--primary); text-decoration: none; }

        @keyframes countUp {
            from { opacity: 0; transform: scale(0.5); }
            to { opacity: 1; transform: scale(1); }
        }
        .score-circle { animation: countUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="bi bi-cpu me-1"></i>Tech<span>Quiz</span></a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link active" href="quiz.php">Quiz</a></li>
                <li class="nav-item"><a class="nav-link" href="leaderboard.php">Leaderboard</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="results-wrapper">
    <div class="container" style="max-width: 600px;">

        <!-- Score Card -->
        <div class="score-card">
            <div class="quiz-complete-tag">// QUIZ COMPLETE</div>
            <h2>Your Results — <?= htmlspecialchars($cat_name) ?></h2>

            <div class="score-circle">
                <div class="score-num"><?= $score ?></div>
                <div class="score-denom">/ <?= $total ?></div>
                <div class="score-percent"><?= $percent ?>%</div>
            </div>

            <div>
                <span class="feedback-msg <?= $msg_class ?>"><?= $msg ?></span>
            </div>
        </div>

        <!-- Performance Breakdown -->
        <div class="perf-card">
            <h5><i class="bi bi-bar-chart me-2" style="color:var(--primary);"></i>YOUR PERFORMANCE</h5>

            <div class="stat-row">
                <span class="stat-label"><i class="bi bi-check-circle text-success"></i> Correct Answers</span>
                <span class="stat-value green"><?= $correct ?> / <?= $questions_total ?></span>
            </div>
            <div class="stat-row">
                <span class="stat-label"><i class="bi bi-x-circle" style="color:var(--accent);"></i> Wrong Answers</span>
                <span class="stat-value red"><?= $wrong ?> / <?= $questions_total ?></span>
            </div>
            <div class="stat-row">
                <span class="stat-label"><i class="bi bi-star" style="color:var(--primary);"></i> Total Score</span>
                <span class="stat-value blue"><?= $score ?> pts</span>
            </div>
            <div class="stat-row">
                <span class="stat-label"><i class="bi bi-percent" style="color:var(--warning);"></i> Accuracy</span>
                <span class="stat-value yellow"><?= $percent ?>%</span>
            </div>
            <div class="stat-row">
                <span class="stat-label"><i class="bi bi-grid" style="color:var(--muted);"></i> Category</span>
                <span class="stat-value" style="color:var(--muted);"><?= htmlspecialchars($cat_name) ?></span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-3 flex-wrap">
            <a href="quiz.php?category=<?= $cat_id ?>" class="btn-result btn-play-again">
                <i class="bi bi-arrow-repeat"></i> Play Again
            </a>
            <a href="leaderboard.php" class="btn-result btn-leaderboard">
                <i class="bi bi-trophy"></i> Leaderboard
            </a>
            <a href="index.php" class="btn-result btn-home">
                <i class="bi bi-house"></i> Home
            </a>
        </div>

    </div>
</div>

<footer>
    <p>© 2026 TechQuiz | All Rights Reserved | Contact: <a href="mailto:info@techquiz.com">info@techquiz.com</a></p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
