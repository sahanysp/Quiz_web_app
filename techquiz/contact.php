<?php
require_once 'db.php';

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!$name || !$email || !$message) {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($message) < 10) {
        $error = "Message must be at least 10 characters.";
    } else {
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);
        if ($stmt->execute()) {
            $success = "Thank you, " . htmlspecialchars($name) . "! Your message has been sent.";
        } else {
            $error = "Failed to send message. Please try again.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact – TechQuiz</title>
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
        .page-tag { font-family: 'Space Mono', monospace; font-size: 0.75rem; color: var(--primary); letter-spacing: 0.2em; margin-bottom: 0.5rem; }
        .page-header h1 { font-weight: 800; font-size: 2.5rem; letter-spacing: -1.5px; }

        .contact-section { flex: 1; padding: 0 0 4rem; }

        .contact-card {
            background: var(--card-bg);
            border: 1px solid rgba(0,255,255,0.12);
            border-radius: 16px;
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
        }
        .contact-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }

        .form-label {
            color: var(--muted);
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            margin-bottom: 0.4rem;
        }
        .required-star { color: var(--accent); }
        .form-control, .form-select {
            background: var(--dark3);
            border: 1px solid rgba(255,255,255,0.1);
            color: var(--text);
            border-radius: 6px;
            padding: 0.75rem 1rem;
            font-family: 'Syne', sans-serif;
        }
        .form-control:focus, .form-select:focus {
            background: var(--dark3);
            border-color: var(--primary);
            color: var(--text);
            box-shadow: 0 0 0 3px rgba(0,255,255,0.1);
        }
        .form-control::placeholder { color: rgba(136,136,153,0.5); }
        textarea.form-control { resize: vertical; min-height: 140px; }

        .btn-send {
            background: var(--primary);
            color: var(--dark);
            border: none;
            padding: 0.85rem 2.5rem;
            font-family: 'Space Mono', monospace;
            font-weight: 700;
            font-size: 0.9rem;
            border-radius: 6px;
            transition: all 0.2s;
        }
        .btn-send:hover {
            background: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(0,255,255,0.25);
        }

        .alert-success-custom {
            background: rgba(0,255,136,0.1);
            border: 1px solid rgba(0,255,136,0.3);
            color: #00ff88;
            border-radius: 8px;
            padding: 1rem 1.2rem;
            margin-bottom: 1.5rem;
        }
        .alert-error-custom {
            background: rgba(255,45,120,0.1);
            border: 1px solid rgba(255,45,120,0.3);
            color: #ff6699;
            border-radius: 8px;
            padding: 1rem 1.2rem;
            margin-bottom: 1.5rem;
        }

        /* Info Cards */
        .info-card {
            background: var(--card-bg);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            height: 100%;
        }
        .info-icon {
            width: 44px; height: 44px;
            background: rgba(0,255,255,0.1);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            color: var(--primary);
            flex-shrink: 0;
        }
        .info-label {
            font-family: 'Space Mono', monospace;
            font-size: 0.7rem;
            color: var(--muted);
            letter-spacing: 0.1em;
            margin-bottom: 0.2rem;
        }
        .info-value {
            font-size: 0.9rem;
            font-weight: 600;
        }

        .char-count { font-family: 'Space Mono', monospace; font-size: 0.75rem; color: var(--muted); text-align: right; }

        footer {
            background: var(--dark2);
            border-top: 1px solid rgba(255,255,255,0.07);
            padding: 1.5rem 0;
            text-align: center;
            color: var(--muted);
            font-size: 0.85rem;
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
                <li class="nav-item"><a class="nav-link" href="leaderboard.php">Leaderboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
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
        <div class="page-tag">// REACH OUT</div>
        <h1>Get In Touch</h1>
        <p style="color:var(--muted);">Have questions or feedback? We'd love to hear from you.</p>
    </div>
</div>

<div class="contact-section">
    <div class="container">
        <div class="row g-4">
            <!-- Form -->
            <div class="col-lg-8">
                <div class="contact-card">
                    <?php if ($success): ?>
                        <div class="alert-success-custom"><i class="bi bi-check-circle me-2"></i><?= $success ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert-error-custom"><i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="" id="contactForm" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">NAME <span class="required-star">*</span></label>
                                <input type="text" name="name" id="fname" class="form-control" placeholder="Enter your name" required>
                                <div class="invalid-feedback" id="nameError"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">EMAIL <span class="required-star">*</span></label>
                                <input type="email" name="email" id="femail" class="form-control" placeholder="Enter your email" required>
                                <div class="invalid-feedback" id="emailError"></div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">MESSAGE <span class="required-star">*</span></label>
                                <textarea name="message" id="fmessage" class="form-control" placeholder="Type your message here..." required oninput="updateChar()"></textarea>
                                <div class="char-count mt-1"><span id="charCount">0</span> characters</div>
                                <div class="invalid-feedback" id="msgError"></div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn-send"><i class="bi bi-send me-2"></i>Send Message</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-4">
                <div class="d-flex flex-column gap-3">
                    <div class="info-card">
                        <div class="info-icon"><i class="bi bi-telephone"></i></div>
                        <div>
                            <div class="info-label">PHONE</div>
                            <div class="info-value">+94 702 715 811</div>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="info-icon"><i class="bi bi-envelope"></i></div>
                        <div>
                            <div class="info-label">EMAIL</div>
                            <div class="info-value">sahan2003ysp@gmail.com</div>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="info-icon"><i class="bi bi-geo-alt"></i></div>
                        <div>
                            <div class="info-label">ADDRESS</div>
                            <div class="info-value">+94 702 715 811</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer>
    <p>© 2024 TechQuiz | All Rights Reserved | Contact: <a href="mailto:info@techquiz.com">info@techquiz.com</a></p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function updateChar() {
    document.getElementById('charCount').textContent = document.getElementById('fmessage').value.length;
}

document.getElementById('contactForm').addEventListener('submit', function(e) {
    let valid = true;

    const name = document.getElementById('fname');
    const email = document.getElementById('femail');
    const msg = document.getElementById('fmessage');

    // Reset
    [name, email, msg].forEach(f => { f.classList.remove('is-invalid', 'is-valid'); });

    // Validate name
    if (!name.value.trim()) {
        name.classList.add('is-invalid');
        document.getElementById('nameError').textContent = 'Name is required.';
        valid = false;
    } else { name.classList.add('is-valid'); }

    // Validate email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email.value.trim()) {
        email.classList.add('is-invalid');
        document.getElementById('emailError').textContent = 'Email is required.';
        valid = false;
    } else if (!emailRegex.test(email.value)) {
        email.classList.add('is-invalid');
        document.getElementById('emailError').textContent = 'Please enter a valid email.';
        valid = false;
    } else { email.classList.add('is-valid'); }

    // Validate message
    if (!msg.value.trim()) {
        msg.classList.add('is-invalid');
        document.getElementById('msgError').textContent = 'Message is required.';
        valid = false;
    } else if (msg.value.trim().length < 10) {
        msg.classList.add('is-invalid');
        document.getElementById('msgError').textContent = 'Message must be at least 10 characters.';
        valid = false;
    } else { msg.classList.add('is-valid'); }

    if (!valid) e.preventDefault();
});
</script>
</body>
</html>
