<?php
require_once 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                header("Location: index.php");
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "User not found.";
        }
        $stmt->close();
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – TechQuiz</title>
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
        .navbar-brand {
            font-family: 'Space Mono', monospace;
            font-size: 1.4rem;
            color: var(--primary) !important;
        }
        .navbar-brand span { color: var(--accent); }
        .nav-link { color: var(--muted) !important; font-weight: 600; }
        .nav-link:hover { color: var(--primary) !important; }
        .auth-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
            position: relative;
        }
        .auth-wrapper::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse 60% 60% at 50% 50%, rgba(0,255,255,0.05) 0%, transparent 70%);
        }
        .auth-card {
            background: var(--card-bg);
            border: 1px solid rgba(0,255,255,0.15);
            border-radius: 16px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            position: relative;
            overflow: hidden;
        }
        .auth-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }
        .auth-tag {
            font-family: 'Space Mono', monospace;
            font-size: 0.7rem;
            color: var(--primary);
            letter-spacing: 0.2em;
            margin-bottom: 0.5rem;
        }
        .auth-card h2 {
            font-weight: 800;
            font-size: 1.8rem;
            letter-spacing: -1px;
            margin-bottom: 0.3rem;
        }
        .auth-card .subtitle {
            color: var(--muted);
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }
        .form-label {
            color: var(--muted);
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            margin-bottom: 0.4rem;
        }
        .form-control {
            background: var(--dark3);
            border: 1px solid rgba(255,255,255,0.1);
            color: var(--text);
            border-radius: 6px;
            padding: 0.7rem 1rem;
            font-family: 'Syne', sans-serif;
        }
        .form-control:focus {
            background: var(--dark3);
            border-color: var(--primary);
            color: var(--text);
            box-shadow: 0 0 0 3px rgba(0,255,255,0.1);
        }
        .form-control::placeholder { color: rgba(136,136,153,0.5); }
        .btn-auth {
            background: var(--primary);
            color: var(--dark);
            border: none;
            width: 100%;
            padding: 0.85rem;
            font-family: 'Space Mono', monospace;
            font-weight: 700;
            font-size: 0.9rem;
            border-radius: 6px;
            transition: all 0.2s;
            margin-top: 0.5rem;
        }
        .btn-auth:hover {
            background: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(0,255,255,0.25);
        }
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.88rem;
            color: var(--muted);
        }
        .auth-footer a { color: var(--primary); text-decoration: none; }
        .auth-footer a:hover { text-decoration: underline; }
        .alert-error {
            background: rgba(255,45,120,0.1);
            border: 1px solid rgba(255,45,120,0.3);
            color: #ff6699;
            border-radius: 6px;
            padding: 0.75rem 1rem;
            font-size: 0.88rem;
            margin-bottom: 1.5rem;
        }
        footer {
            background: var(--dark2);
            border-top: 1px solid rgba(255,255,255,0.07);
            padding: 1.5rem 0;
            text-align: center;
            color: var(--muted);
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="bi bi-cpu me-1"></i>Tech<span>Quiz</span></a>
        <div class="ms-auto">
            <a class="nav-link d-inline" href="index.php">Home</a>
            <a class="nav-link d-inline ms-3" href="register.php">Register</a>
        </div>
    </div>
</nav>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-tag">// WELCOME BACK</div>
        <h2>Login</h2>
        <p class="subtitle">Enter your credentials to continue</p>

        <?php if ($error): ?>
            <div class="alert-error"><i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">USERNAME OR EMAIL</label>
                <input type="text" name="username" class="form-control" placeholder="Enter username or email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">PASSWORD</label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn-auth"><i class="bi bi-box-arrow-in-right me-2"></i>Login</button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </div>
</div>

<footer>
    <p>© 2026 TechQuiz | All Rights Reserved | Contact: <a href="mailto:info@techquiz.com" style="color: #0ff; text-decoration:none;">info@techquiz.com</a></p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
