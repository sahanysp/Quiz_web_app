<?php
require_once 'db.php';

$cat_filter = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;

// Get categories
$cats_res = $conn->query("SELECT * FROM categories");
$categories = [];
while ($r = $cats_res->fetch_assoc()) $categories[] = $r;

// Build query
if ($cat_filter > 0) {
    $stmt = $conn->prepare("
        SELECT u.username, s.score, s.total, s.category_id, c.name AS cat_name, s.played_at,
               ROUND((s.score / s.total) * 100) AS percent
        FROM scores s
        JOIN users u ON s.user_id = u.id
        JOIN categories c ON s.category_id = c.id
        WHERE s.category_id = ?
        ORDER BY s.score DESC, s.played_at ASC
        LIMIT 20
    ");
    $stmt->bind_param("i", $cat_filter);
} else {
    $stmt = $conn->prepare("
        SELECT u.username, s.score, s.total, s.category_id, c.name AS cat_name, s.played_at,
               ROUND((s.score / s.total) * 100) AS percent
        FROM scores s
        JOIN users u ON s.user_id = u.id
        JOIN categories c ON s.category_id = c.id
        ORDER BY s.score DESC, s.played_at ASC
        LIMIT 20
    ");
}
$stmt->execute();
$result = $stmt->get_result();
$rows = [];
while ($r = $result->fetch_assoc()) $rows[] = $r;
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard – TechQuiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #0ff;
            --accent: #ff2d78;
            --gold: #ffd700;
            --silver: #c0c0c0;
            --bronze: #cd7f32;
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
        .navbar-brand { font-family: 'Space Mono', monospace; font-size: 1.4rem; color: var(--primary) !important; }
        .navbar-brand span { color: var(--accent); }
        .nav-link { color: var(--muted) !important; font-weight: 600; }
        .nav-link:hover, .nav-link.active { color: var(--primary) !important; }

        .page-header {
            padding: 3rem 0 2rem;
            text-align: center;
            position: relative;
        }
        .page-header::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse 60% 80% at 50% 0%, rgba(0,255,255,0.06) 0%, transparent 60%);
        }
        .page-tag {
            font-family: 'Space Mono', monospace;
            font-size: 0.75rem;
            color: var(--primary);
            letter-spacing: 0.2em;
            margin-bottom: 0.5rem;
        }
        .page-header h1 { font-weight: 800; font-size: 2.5rem; letter-spacing: -1.5px; }

        .filter-bar {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
            justify-content: center;
        }
        .filter-btn {
            font-family: 'Space Mono', monospace;
            font-size: 0.75rem;
            padding: 0.5rem 1.2rem;
            border-radius: 4px;
            border: 1px solid rgba(255,255,255,0.1);
            background: transparent;
            color: var(--muted);
            transition: all 0.2s;
            text-decoration: none;
        }
        .filter-btn:hover, .filter-btn.active {
            border-color: var(--primary);
            color: var(--primary);
        }

        .lb-table-wrap {
            background: var(--card-bg);
            border: 1px solid rgba(0,255,255,0.1);
            border-radius: 12px;
            overflow: hidden;
        }
        .lb-table { width: 100%; border-collapse: collapse; }
        .lb-table thead tr {
            background: var(--dark3);
            border-bottom: 1px solid rgba(0,255,255,0.1);
        }
        .lb-table th {
            padding: 1rem 1.2rem;
            font-family: 'Space Mono', monospace;
            font-size: 0.7rem;
            letter-spacing: 0.15em;
            color: var(--muted);
            font-weight: 400;
        }
        .lb-table td {
            padding: 1rem 1.2rem;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            font-size: 0.9rem;
        }
        .lb-table tr:last-child td { border-bottom: none; }
        .lb-table tbody tr { transition: background 0.2s; }
        .lb-table tbody tr:hover { background: rgba(0,255,255,0.03); }

        .rank-num {
            font-family: 'Space Mono', monospace;
            font-weight: 700;
            font-size: 1rem;
        }
        .rank-1 { color: var(--gold); }
        .rank-2 { color: var(--silver); }
        .rank-3 { color: var(--bronze); }
        .rank-other { color: var(--muted); }

        .username-cell { font-weight: 700; }
        .score-cell {
            font-family: 'Space Mono', monospace;
            color: var(--primary);
            font-weight: 700;
        }
        .percent-badge {
            font-family: 'Space Mono', monospace;
            font-size: 0.75rem;
            padding: 0.2rem 0.6rem;
            border-radius: 3px;
        }
        .pct-high { background: rgba(0,255,136,0.15); color: #00ff88; }
        .pct-mid { background: rgba(255,187,0,0.15); color: #ffbb00; }
        .pct-low { background: rgba(255,45,120,0.15); color: #ff6699; }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--muted);
        }
        .empty-state i { font-size: 3rem; margin-bottom: 1rem; display: block; }

        footer {
            background: var(--dark2);
            border-top: 1px solid rgba(255,255,255,0.07);
            padding: 1.5rem 0;
            text-align: center;
            color: var(--muted);
            font-size: 0.85rem;
            margin-top: auto;
        }
        footer a { color: var(--primary); text-decoration: none; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="bi bi-cpu me-1"></i>Tech<span>Quiz</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon" style="filter:invert(1);"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto gap-2">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="quiz.php">Quiz</a></li>
                <li class="nav-item"><a class="nav-link active" href="leaderboard.php">Leaderboard</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="page-header position-relative">
    <div class="container">
        <div class="page-tag">// TOP PLAYERS</div>
        <h1><i class="bi bi-trophy me-2" style="color:var(--gold);"></i>Leaderboard</h1>
        <p style="color:var(--muted);">See who's dominating the tech quiz arena</p>
    </div>
</div>

<div class="container pb-5">
    <!-- Filter -->
    <div class="filter-bar">
        <a href="leaderboard.php" class="filter-btn <?= $cat_filter === 0 ? 'active' : '' ?>">All Categories</a>
        <?php foreach ($categories as $cat): ?>
            <a href="leaderboard.php?cat=<?= $cat['id'] ?>" class="filter-btn <?= $cat_filter === (int)$cat['id'] ? 'active' : '' ?>">
                <?= htmlspecialchars($cat['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="lb-table-wrap">
        <?php if (empty($rows)): ?>
            <div class="empty-state">
                <i class="bi bi-hourglass"></i>
                <p>No scores yet. Be the first to play!</p>
                <a href="quiz.php" style="color:var(--primary);">Start a Quiz</a>
            </div>
        <?php else: ?>
        <table class="lb-table">
            <thead>
                <tr>
                    <th>RANK</th>
                    <th>PLAYER</th>
                    <th>CATEGORY</th>
                    <th>SCORE</th>
                    <th>ACCURACY</th>
                    <th>DATE</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $i => $row):
                    $rank = $i + 1;
                    $rankClass = $rank === 1 ? 'rank-1' : ($rank === 2 ? 'rank-2' : ($rank === 3 ? 'rank-3' : 'rank-other'));
                    $rankIcon = $rank === 1 ? '🥇' : ($rank === 2 ? '🥈' : ($rank === 3 ? '🥉' : "#$rank"));
                    $pctClass = $row['percent'] >= 70 ? 'pct-high' : ($row['percent'] >= 50 ? 'pct-mid' : 'pct-low');
                    $date = date('M d, Y', strtotime($row['played_at']));
                    $isMe = isset($_SESSION['username']) && $_SESSION['username'] === $row['username'];
                ?>
                <tr <?= $isMe ? 'style="background:rgba(0,255,255,0.04);"' : '' ?>>
                    <td><span class="rank-num <?= $rankClass ?>"><?= $rankIcon ?></span></td>
                    <td class="username-cell">
                        <?= htmlspecialchars($row['username']) ?>
                        <?php if ($isMe): ?><span style="font-size:0.7rem;color:var(--primary);margin-left:0.4rem;">(you)</span><?php endif; ?>
                    </td>
                    <td style="color:var(--muted);font-size:0.85rem;"><?= htmlspecialchars($row['cat_name']) ?></td>
                    <td class="score-cell"><?= $row['score'] ?> pts</td>
                    <td><span class="percent-badge <?= $pctClass ?>"><?= $row['percent'] ?>%</span></td>
                    <td style="color:var(--muted);font-size:0.82rem;font-family:'Space Mono',monospace;"><?= $date ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<footer>
    <p>© 2026 TechQuiz | All Rights Reserved | Contact: <a href="mailto:info@techquiz.com">info@techquiz.com</a></p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
