<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (is_logged_in()) {
    redirect_to('../dashboard.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize_input($_POST['username'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || strlen($username) < 3) {
        $errors[] = 'Username must be at least 3 characters.';
    }

    if (!is_valid_email($email)) {
        $errors[] = 'Please provide a valid email.';
    }

    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if (!$errors) {
        $checkStmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
        $checkStmt->execute(['email' => $email]);

        if ($checkStmt->fetch()) {
            $errors[] = 'Email already exists. Please login instead.';
        }
    }

    if (!$errors) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $insertStmt = $pdo->prepare(
            'INSERT INTO users (username, email, password, created_at) VALUES (:username, :email, :password, NOW())'
        );

        $insertStmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $passwordHash,
        ]);

        set_flash('success', 'Registration successful. Please login.');
        redirect_to('login.php');
    }
}

$successMessage = get_flash('success');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <main class="page-container">
    <h1>Create Account</h1>

    <?php if ($successMessage): ?>
      <p class="flash success"><?php echo $successMessage; ?></p>
    <?php endif; ?>

    <?php if ($errors): ?>
      <ul class="flash error">
        <?php foreach ($errors as $error): ?>
          <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <form id="register-form" method="post" novalidate>
      <label for="username">Username</label>
      <input type="text" id="username" name="username" required minlength="3" value="<?php echo htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

      <label for="email">Email</label>
      <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required minlength="6">

      <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
  </main>

  <script src="../js/validation.js"></script>
  <script>
    attachValidation('register-form', [
      { id: 'username', label: 'Username', required: true, minLength: 3 },
      { id: 'email', label: 'Email', required: true, type: 'email' },
      { id: 'password', label: 'Password', required: true, minLength: 6 },
    ]);
  </script>
</body>
</html>
