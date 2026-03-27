<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/api.php';

api_bootstrap('POST');

require_once __DIR__ . '/../includes/db.php';

$input = api_json_input();
$name = sanitize_input((string) ($input['name'] ?? ''));
$email = sanitize_input((string) ($input['email'] ?? ''));
$message = sanitize_input((string) ($input['message'] ?? ''));

$errors = [];

if ($name === '') {
    $errors[] = 'Name is required.';
}

if (!is_valid_email($email)) {
    $errors[] = 'Please provide a valid email.';
}

if ($message === '' || strlen($message) < 10) {
    $errors[] = 'Message must be at least 10 characters.';
}

if ($errors) {
    api_response(422, ['errors' => $errors]);
}

$insertStmt = $pdo->prepare(
    'INSERT INTO messages (name, email, message, created_at) VALUES (:name, :email, :message, NOW())'
);

$insertStmt->execute([
    'name' => $name,
    'email' => $email,
    'message' => $message,
]);

api_response(201, ['message' => 'Your message has been submitted successfully.']);
