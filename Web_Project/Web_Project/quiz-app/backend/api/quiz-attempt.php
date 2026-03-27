<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/api.php';

api_bootstrap('POST');

require_once __DIR__ . '/../includes/db.php';

if (!is_logged_in()) {
    api_response(401, ['error' => 'Please log in to save quiz attempts.']);
}

$input = api_json_input();
$score = (int) ($input['score'] ?? -1);
$totalQuestions = (int) ($input['total_questions'] ?? -1);

if ($score < 0 || $totalQuestions <= 0 || $score > $totalQuestions) {
    api_response(422, ['error' => 'Invalid score payload.']);
}

$insertStmt = $pdo->prepare(
    'INSERT INTO quiz_attempts (user_id, score, total_questions, submitted_at) VALUES (:user_id, :score, :total_questions, NOW())'
);

$insertStmt->execute([
    'user_id' => (int) $_SESSION['user_id'],
    'score' => $score,
    'total_questions' => $totalQuestions,
]);

api_response(201, ['message' => 'Quiz attempt saved.']);
