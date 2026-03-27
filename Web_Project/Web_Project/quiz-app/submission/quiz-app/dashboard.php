<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

require_login();

$userId = (int) $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User';

$attemptStmt = $pdo->prepare('SELECT score, total_questions, submitted_at FROM quiz_attempts WHERE user_id = :user_id ORDER BY submitted_at DESC LIMIT 10');
$attemptStmt->execute(['user_id' => $userId]);
$attempts = $attemptStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <nav class="top-nav">
    <a href="index.php">Home</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="contact.php">Contact</a>
    <a href="auth/logout.php">Logout</a>
  </nav>

  <main class="page-container">
    <h1>Welcome, <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></h1>
    <p>You are logged in. This is your backend dashboard.</p>

    <h2>Recent Quiz Attempts</h2>
    <?php if (!$attempts): ?>
      <p>No attempts found yet.</p>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Score</th>
            <th>Total Questions</th>
            <th>Submitted At</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($attempts as $attempt): ?>
            <tr>
              <td><?php echo (int) $attempt['score']; ?></td>
              <td><?php echo (int) $attempt['total_questions']; ?></td>
              <td><?php echo htmlspecialchars($attempt['submitted_at'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <div class="actions">
      <a class="btn secondary" href="contact.php">Contact Form</a>
      <a class="btn" href="auth/logout.php">Logout</a>
    </div>
  </main>
</body>
</html>
