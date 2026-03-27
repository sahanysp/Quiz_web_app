<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/api.php';

api_bootstrap('POST');

require_once __DIR__ . '/../includes/db.php';

if (!is_logged_in()) {
    api_response(401, ['error' => 'Please log in to perform this action.']);
}

$deleteStmt = $pdo->prepare('DELETE FROM quiz_attempts WHERE user_id = :user_id');
$deleteStmt->execute(['user_id' => (int) $_SESSION['user_id']]);

api_response(200, ['message' => 'History cleared successfully.']);