<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/api.php';

api_bootstrap('POST');

require_once __DIR__ . '/../includes/db.php';

$input = api_json_input();
$email = sanitize_input((string) ($input['email'] ?? ''));
$password = (string) ($input['password'] ?? '');

if (!is_valid_email($email) || $password === '') {
    api_response(422, ['error' => 'Valid email and password are required.']);
}

$userStmt = $pdo->prepare('SELECT id, username, password FROM users WHERE email = :email LIMIT 1');
$userStmt->execute(['email' => $email]);
$user = $userStmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    api_response(401, ['error' => 'Invalid email or password.']);
}

$_SESSION['user_id'] = (int) $user['id'];
$_SESSION['username'] = $user['username'];

api_response(200, [
    'message' => 'Login successful.',
    'user' => [
        'id' => (int) $user['id'],
        'username' => $user['username'],
        'email' => $email,
    ],
]);
