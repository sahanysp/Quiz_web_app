<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (is_logged_in()) {
    redirect_to('../dashboard.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!is_valid_email($email)) {
        $errors[] = 'Please provide a valid email.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if (!$errors) {
        $userStmt = $pdo->prepare('SELECT id, username, password FROM users WHERE email = :email LIMIT 1');
        $userStmt->execute(['email' => $email]);
        $user = $userStmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            $errors[] = 'Invalid email or password.';
        } else {
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['username'] = $user['username'];
            redirect_to('../dashboard.php');
        }
    }
}

$successMessage = get_flash('success');
$errorMessage = get_flash('error');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <main class="page-container">
    <h1>Login</h1>

    <?php if ($successMessage): ?>
      <p class="flash success"><?php echo $successMessage; ?></p>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
      <p class="flash error"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <?php if ($errors): ?>
      <ul class="flash error">
        <?php foreach ($errors as $error): ?>
          <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <form id="login-form" method="post" novalidate>
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>

      <button type="submit">Login</button>
    </form>

    <p>New user? <a href="register.php">Create an account</a></p>
  </main>

  <script src="../js/validation.js"></script>
  <script>
    attachValidation('login-form', [
      { id: 'email', label: 'Email', required: true, type: 'email' },
      { id: 'password', label: 'Password', required: true, minLength: 1 },
    ]);
  </script>
</body>
</html>
