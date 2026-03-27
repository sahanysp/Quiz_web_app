<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/api.php';

api_bootstrap('POST');

require_once __DIR__ . '/../includes/db.php';

$input = api_json_input();
$username = sanitize_input((string) ($input['username'] ?? ''));
$email = sanitize_input((string) ($input['email'] ?? ''));
$password = (string) ($input['password'] ?? '');

$errors = [];

if ($username === '' || strlen($username) < 3) {
    $errors[] = 'Username must be at least 3 characters.';
}

if (!is_valid_email($email)) {
    $errors[] = 'Please provide a valid email.';
}

if (strlen($password) < 6) {
    $errors[] = 'Password must be at least 6 characters.';
}

if ($errors) {
    api_response(422, ['errors' => $errors]);
}

$checkStmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
$checkStmt->execute(['email' => $email]);

if ($checkStmt->fetch()) {
    api_response(409, ['error' => 'Email already exists.']);
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$insertStmt = $pdo->prepare(
    'INSERT INTO users (username, email, password, created_at) VALUES (:username, :email, :password, NOW())'
);

$insertStmt->execute([
    'username' => $username,
    'email' => $email,
    'password' => $passwordHash,
]);

api_response(201, ['message' => 'Registration successful. You can now log in.']);
