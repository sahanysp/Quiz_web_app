<?php
require_once 'db.php';

// Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get categories
$cats = $conn->query("SELECT * FROM categories");
$categories = [];
while ($row = $cats->fetch_assoc()) $categories[] = $row;

// Selected category
$cat_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;
if ($cat_id < 1 || $cat_id > count($categories)) $cat_id = 1;

// Fetch 10 random questions for selected category
$stmt = $conn->prepare("SELECT * FROM questions WHERE category_id = ? ORDER BY RAND() LIMIT 10");
$stmt->bind_param("i", $cat_id);
$stmt->execute();
$result = $stmt->get_result();
$questions = [];
while ($row = $result->fetch_assoc()) $questions[] = $row;
$stmt->close();

$cat_name = '';
foreach ($categories as $c) { if ($c['id'] == $cat_id) $cat_name = $c['name']; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz – TechQuiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #0ff;
            --accent: #ff2d78;
            --success: #00ff88;
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

        /* Category Select Screen */
        .cat-select-screen {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
        }
        .cat-card {
            background: var(--card-bg);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 12px;
            padding: 1.8rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            color: var(--text);
            display: block;
        }
        .cat-card:hover, .cat-card.selected {
            border-color: var(--primary);
            color: var(--text);
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.4), 0 0 20px rgba(0,255,255,0.1);
        }
        .cat-icon { font-size: 2.8rem; margin-bottom: 0.8rem; display: block; }
        .cat-card h5 { font-weight: 700; margin-bottom: 0.3rem; }
        .cat-card small { color: var(--muted); font-size: 0.8rem; }

        /* Quiz Screen */
        .quiz-screen { display: none; flex: 1; padding: 2rem 1rem; }
        .quiz-header {
            background: var(--card-bg);
            border: 1px solid rgba(0,255,255,0.1);
            border-radius: 12px;
            padding: 1.2rem 1.8rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .quiz-progress-text {
            font-family: 'Space Mono', monospace;
            font-size: 0.85rem;
            color: var(--muted);
        }
        .quiz-score-display {
            font-family: 'Space Mono', monospace;
            font-size: 0.85rem;
            color: var(--primary);
        }
        .timer-display {
            font-family: 'Space Mono', monospace;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .timer-display.warning { color: var(--accent); animation: pulse 0.5s infinite; }
        @keyframes pulse { 0%,100% { opacity:1; } 50% { opacity:0.5; } }

        .progress { background: var(--dark3); height: 4px; border-radius: 2px; margin-bottom: 1.5rem; }
        .progress-bar { background: linear-gradient(90deg, var(--primary), var(--accent)); transition: width 0.4s; }

        .question-card {
            background: var(--card-bg);
            border: 1px solid rgba(0,255,255,0.1);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }
        .question-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }
        .question-number {
            font-family: 'Space Mono', monospace;
            font-size: 0.75rem;
            color: var(--primary);
            letter-spacing: 0.15em;
            margin-bottom: 1rem;
        }
        .question-text {
            font-size: 1.2rem;
            font-weight: 700;
            line-height: 1.5;
            margin-bottom: 0;
        }

        .options-list { list-style: none; padding: 0; }
        .option-item {
            background: var(--dark3);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 8px;
            padding: 1rem 1.2rem;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
        }
        .option-item:hover:not(.disabled) {
            border-color: var(--primary);
            background: rgba(0,255,255,0.05);
        }
        .option-item.selected { border-color: var(--primary); background: rgba(0,255,255,0.08); }
        .option-item.correct {
            border-color: var(--success);
            background: rgba(0,255,136,0.1);
            color: var(--success);
        }
        .option-item.wrong {
            border-color: var(--accent);
            background: rgba(255,45,120,0.1);
            color: #ff6699;
        }
        .option-item.reveal-correct {
            border-color: var(--success);
            background: rgba(0,255,136,0.05);
            color: var(--success);
        }
        .option-item.disabled { cursor: default; }
        .option-label {
            font-family: 'Space Mono', monospace;
            font-size: 0.8rem;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .option-item.correct .option-label,
        .option-item.reveal-correct .option-label { border-color: var(--success); color: var(--success); }
        .option-item.wrong .option-label { border-color: var(--accent); color: var(--accent); }
        .option-text { font-size: 0.95rem; font-weight: 600; }
        .option-icon { margin-left: auto; display: none; }
        .option-item.correct .option-icon,
        .option-item.wrong .option-icon,
        .option-item.reveal-correct .option-icon { display: block; }

        .feedback-box {
            border-radius: 8px;
            padding: 1rem 1.2rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            display: none;
        }
        .feedback-box.correct { background: rgba(0,255,136,0.1); border: 1px solid rgba(0,255,136,0.3); color: var(--success); }
        .feedback-box.wrong { background: rgba(255,45,120,0.1); border: 1px solid rgba(255,45,120,0.3); color: #ff6699; }

        .btn-quiz {
            font-family: 'Space Mono', monospace;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 0.8rem 2rem;
            border-radius: 6px;
            border: none;
            transition: all 0.2s;
        }
        .btn-submit {
            background: var(--primary);
            color: var(--dark);
        }
        .btn-submit:hover { background: #fff; transform: translateY(-1px); }
        .btn-submit:disabled { background: var(--muted); cursor: not-allowed; transform: none; }
        .btn-next {
            background: transparent;
            color: var(--text);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .btn-next:hover { border-color: var(--text); }
        .btn-next:disabled { opacity: 0.3; cursor: not-allowed; }

        /* Section title */
        .section-label {
            font-family: 'Space Mono', monospace;
            font-size: 0.75rem;
            color: var(--primary);
            letter-spacing: 0.2em;
        }
        .section-title { font-weight: 800; letter-spacing: -1px; }

        footer {
            background: var(--dark2);
            border-top: 1px solid rgba(255,255,255,0.07);
            padding: 1.5rem 0;
            text-align: center;
            color: var(--muted);
            font-size: 0.85rem;
        }
        footer a { color: var(--primary); text-decoration: none; }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .slide-in { animation: slideIn 0.35s ease forwards; }
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
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link active" href="quiz.php">Quiz</a></li>
                <li class="nav-item"><a class="nav-link" href="leaderboard.php">Leaderboard</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <li class="nav-item">
                    <span class="nav-link" style="color: var(--primary)!important;">
                        <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($_SESSION['username']) ?>
                    </span>
                </li>
                <li class="nav-item"><a class="nav-link" href="logout.php" style="color: var(--muted)!important;">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Category Select Screen -->
<div class="cat-select-screen" id="catScreen">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label">PICK YOUR CHALLENGE</div>
            <h2 class="section-title" style="font-size:2.2rem;">Choose a Category</h2>
            <p style="color:var(--muted);">10 random questions · 30 seconds each · Instant feedback</p>
        </div>
        <div class="row g-4 justify-content-center">
            <?php
            $icons = ['🌐','🔗','💻','🔒'];
            $descs = ['HTML, CSS, JS, PHP','TCP/IP, DNS, Protocols','Algorithms, OOP, DSA','SSL, Threats, Crypto'];
            foreach ($categories as $i => $cat): ?>
            <div class="col-6 col-md-3">
                <div class="cat-card <?= $cat['id'] == $cat_id ? 'selected' : '' ?>" onclick="startQuiz(<?= $cat['id'] ?>)">
                    <span class="cat-icon"><?= $icons[$i] ?></span>
                    <h5><?= htmlspecialchars($cat['name']) ?></h5>
                    <small><?= $descs[$i] ?></small>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <button class="btn-quiz btn-submit" onclick="beginQuiz()" style="font-size:1rem;padding:0.9rem 3rem;">
                <i class="bi bi-play-fill me-2"></i>Start Quiz
            </button>
        </div>
    </div>
</div>

<!-- Quiz Screen -->
<div class="quiz-screen container" id="quizScreen">
    <div class="quiz-header">
        <div class="quiz-progress-text">
            <span id="qProgress">Question 1 of 10</span> &nbsp;|&nbsp; <span id="catLabel" style="color:var(--primary);"><?= htmlspecialchars($cat_name) ?></span>
        </div>
        <div class="timer-display" id="timerDisplay">
            <i class="bi bi-clock"></i> <span id="timerVal">30</span>s
        </div>
        <div class="quiz-score-display">
            SCORE: <span id="scoreDisplay">0</span> pts
        </div>
    </div>

    <div class="progress mb-3">
        <div class="progress-bar" id="progressBar" style="width: 10%"></div>
    </div>

    <div class="question-card slide-in" id="questionCard">
        <div class="question-number" id="qNum">QUESTION 1 / 10</div>
        <div class="question-text" id="qText">Loading...</div>
    </div>

    <ul class="options-list" id="optionsList"></ul>

    <div class="feedback-box" id="feedbackBox"></div>

    <div class="d-flex gap-3">
        <button class="btn-quiz btn-submit" id="btnSubmit" onclick="submitAnswer()">
            <i class="bi bi-check-lg me-1"></i> Submit Answer
        </button>
        <button class="btn-quiz btn-next" id="btnNext" onclick="nextQuestion()" disabled>
            Next Question <i class="bi bi-arrow-right ms-1"></i>
        </button>
    </div>
</div>

<footer>
    <p>© 2024 TechQuiz | All Rights Reserved | Contact: <a href="mailto:info@techquiz.com">info@techquiz.com</a></p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Questions from PHP
    const questions = <?= json_encode($questions) ?>;
    const categoryId = <?= $cat_id ?>;
    const userId = <?= $_SESSION['user_id'] ?>;

    let currentQ = 0;
    let score = 0;
    let selectedOption = null;
    let answered = false;
    let timerInterval = null;
    let timeLeft = 30;
    let selectedCatId = categoryId;

    // Category selection
    function startQuiz(catId) {
        selectedCatId = catId;
        document.querySelectorAll('.cat-card').forEach(c => c.classList.remove('selected'));
        event.currentTarget.classList.add('selected');
    }

    function beginQuiz() {
        window.location.href = 'quiz.php?category=' + selectedCatId;
    }

    // Start quiz if questions loaded
    if (questions.length > 0) {
        document.getElementById('catScreen').style.display = 'none';
        document.getElementById('quizScreen').style.display = 'block';
        loadQuestion();
    }

    function loadQuestion() {
        answered = false;
        selectedOption = null;
        timeLeft = 30;

        const q = questions[currentQ];
        document.getElementById('qNum').textContent = `QUESTION ${currentQ + 1} / ${questions.length}`;
        document.getElementById('qProgress').textContent = `Question ${currentQ + 1} of ${questions.length}`;
        document.getElementById('qText').textContent = q.question;

        const progress = ((currentQ + 1) / questions.length) * 100;
        document.getElementById('progressBar').style.width = progress + '%';

        // Build options
        const opts = [
            { key: 'A', text: q.option_a },
            { key: 'B', text: q.option_b },
            { key: 'C', text: q.option_c },
            { key: 'D', text: q.option_d },
        ];
        // Shuffle options
        opts.sort(() => Math.random() - 0.5);

        const list = document.getElementById('optionsList');
        list.innerHTML = '';
        opts.forEach(opt => {
            const li = document.createElement('li');
            li.className = 'option-item';
            li.dataset.key = opt.key;
            li.innerHTML = `
                <span class="option-label">${opt.key}</span>
                <span class="option-text">${opt.text}</span>
                <i class="bi bi-check-circle option-icon"></i>
            `;
            li.addEventListener('click', () => selectOption(li, opt.key));
            list.appendChild(li);
        });

        // Reset UI
        document.getElementById('feedbackBox').style.display = 'none';
        document.getElementById('feedbackBox').className = 'feedback-box';
        document.getElementById('btnSubmit').disabled = true;
        document.getElementById('btnNext').disabled = true;

        const timerDisplay = document.getElementById('timerDisplay');
        timerDisplay.classList.remove('warning');

        // Animate card
        const card = document.getElementById('questionCard');
        card.classList.remove('slide-in');
        void card.offsetWidth;
        card.classList.add('slide-in');

        startTimer();
    }

    function selectOption(el, key) {
        if (answered) return;
        document.querySelectorAll('.option-item').forEach(o => o.classList.remove('selected'));
        el.classList.add('selected');
        selectedOption = key;
        document.getElementById('btnSubmit').disabled = false;
    }

    function startTimer() {
        clearInterval(timerInterval);
        document.getElementById('timerVal').textContent = timeLeft;
        timerInterval = setInterval(() => {
            timeLeft--;
            document.getElementById('timerVal').textContent = timeLeft;
            const timerDisplay = document.getElementById('timerDisplay');
            if (timeLeft <= 10) timerDisplay.classList.add('warning');
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                autoSubmit();
            }
        }, 1000);
    }

    function autoSubmit() {
        if (!answered) {
            answered = true;
            document.getElementById('btnSubmit').disabled = true;
            revealAnswer(null);
        }
    }

    function submitAnswer() {
        if (!selectedOption || answered) return;
        clearInterval(timerInterval);
        answered = true;
        document.getElementById('btnSubmit').disabled = true;
        revealAnswer(selectedOption);
    }

    function revealAnswer(chosen) {
        const q = questions[currentQ];
        const correct = q.correct_answer;
        const feedback = document.getElementById('feedbackBox');

        document.querySelectorAll('.option-item').forEach(opt => {
            opt.classList.add('disabled');
            opt.style.pointerEvents = 'none';

            if (opt.dataset.key === correct) {
                opt.classList.add('reveal-correct');
                const icon = opt.querySelector('.option-icon');
                icon.className = 'bi bi-check-circle option-icon';
            }
            if (chosen && opt.dataset.key === chosen && chosen !== correct) {
                opt.classList.remove('selected');
                opt.classList.add('wrong');
                const icon = opt.querySelector('.option-icon');
                icon.className = 'bi bi-x-circle option-icon';
            }
        });

        if (chosen === correct) {
            score += 10;
            document.getElementById('scoreDisplay').textContent = score;
            feedback.className = 'feedback-box correct';
            feedback.innerHTML = `<i class="bi bi-check-circle me-2"></i>Correct! +10 points`;
        } else if (chosen) {
            feedback.className = 'feedback-box wrong';
            feedback.innerHTML = `<i class="bi bi-x-circle me-2"></i>Wrong! The correct answer was <strong>${correct}</strong>`;
        } else {
            feedback.className = 'feedback-box wrong';
            feedback.innerHTML = `<i class="bi bi-alarm me-2"></i>Time's up! The correct answer was <strong>${correct}</strong>`;
        }

        feedback.style.display = 'block';

        if (currentQ < questions.length - 1) {
            document.getElementById('btnNext').disabled = false;
        } else {
            document.getElementById('btnNext').textContent = 'View Results';
            document.getElementById('btnNext').innerHTML = 'View Results <i class="bi bi-bar-chart ms-1"></i>';
            document.getElementById('btnNext').disabled = false;
            document.getElementById('btnNext').onclick = finishQuiz;
        }
    }

    function nextQuestion() {
        currentQ++;
        loadQuestion();
    }

    function finishQuiz() {
        // Save score via AJAX then redirect
        fetch('save_score.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                user_id: userId,
                category_id: categoryId,
                score: score,
                total: questions.length * 10
            })
        })
        .then(() => {
            window.location.href = `result.php?score=${score}&total=${questions.length * 10}&correct=${score/10}&wrong=${questions.length - score/10}&cat=${categoryId}`;
        })
        .catch(() => {
            window.location.href = `result.php?score=${score}&total=${questions.length * 10}&correct=${score/10}&wrong=${questions.length - score/10}&cat=${categoryId}`;
        });
    }
</script>
</body>
</html>
