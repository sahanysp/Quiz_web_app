<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

if (is_logged_in()) {
    redirect_to('dashboard.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QuizMaster Backend Home</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <nav class="top-nav">
    <a href="index.php">Home</a>
    <a href="contact.php">Contact</a>
    <a href="auth/login.php">Login</a>
    <a href="auth/register.php">Register</a>
  </nav>

  <main class="page-container">
    <h1>QuizMaster Backend Portal</h1>
    <p>This portal implements the PHP + MySQL requirements from Phase 3.</p>
    <div class="actions">
      <a class="btn" href="auth/register.php">Register</a>
      <a class="btn" href="auth/login.php">Login</a>
      <a class="btn secondary" href="contact.php">Contact</a>
    </div>
  </main>
</body>
</html>
