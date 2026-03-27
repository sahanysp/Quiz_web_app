<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/api.php';

api_bootstrap('GET');

require_once __DIR__ . '/../includes/db.php';

if (!is_logged_in()) {
    api_response(200, ['authenticated' => false]);
}

$userId = (int) $_SESSION['user_id'];
$username = (string) ($_SESSION['username'] ?? 'User');

$attemptStmt = $pdo->prepare(
    'SELECT score, total_questions, submitted_at FROM quiz_attempts WHERE user_id = :user_id ORDER BY submitted_at DESC LIMIT 10'
);
$attemptStmt->execute(['user_id' => $userId]);
$attempts = $attemptStmt->fetchAll();

api_response(200, [
    'authenticated' => true,
    'user' => [
        'id' => $userId,
        'username' => $username,
    ],
    'attempts' => $attempts,
]);
