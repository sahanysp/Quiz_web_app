<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$errors = [];
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize_input($_POST['name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $message = sanitize_input($_POST['message'] ?? '');

    if ($name === '') {
        $errors[] = 'Name is required.';
    }

    if (!is_valid_email($email)) {
        $errors[] = 'Please provide a valid email.';
    }

    if ($message === '' || strlen($message) < 10) {
        $errors[] = 'Message must be at least 10 characters.';
    }

    if (!$errors) {
        $insertStmt = $pdo->prepare(
            'INSERT INTO messages (name, email, message, created_at) VALUES (:name, :email, :message, NOW())'
        );

        $insertStmt->execute([
            'name' => $name,
            'email' => $email,
            'message' => $message,
        ]);

        $success = 'Your message has been submitted successfully.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <nav class="top-nav">
    <a href="index.php">Home</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="contact.php">Contact</a>
    <a href="auth/login.php">Login</a>
  </nav>

  <main class="page-container">
    <h1>Contact Us</h1>

    <?php if ($success): ?>
      <p class="flash success"><?php echo $success; ?></p>
    <?php endif; ?>

    <?php if ($errors): ?>
      <ul class="flash error">
        <?php foreach ($errors as $error): ?>
          <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <form id="contact-form" method="post" novalidate>
      <label for="name">Name</label>
      <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

      <label for="email">Email</label>
      <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

      <label for="message">Message</label>
      <textarea id="message" name="message" rows="5" required><?php echo htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>

      <button type="submit">Send Message</button>
    </form>

    <p><a href="index.php">Back to Home</a></p>
  </main>

  <script src="js/validation.js"></script>
  <script>
    attachValidation('contact-form', [
      { id: 'name', label: 'Name', required: true, minLength: 1 },
      { id: 'email', label: 'Email', required: true, type: 'email' },
      { id: 'message', label: 'Message', required: true, minLength: 10 },
    ]);
  </script>
</body>
</html>
