<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $user_id     = (int)($data['user_id'] ?? 0);
    $category_id = (int)($data['category_id'] ?? 0);
    $score       = (int)($data['score'] ?? 0);
    $total       = (int)($data['total'] ?? 0);

    if ($user_id && $category_id) {
        $stmt = $conn->prepare("INSERT INTO scores (user_id, category_id, score, total) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiii", $user_id, $category_id, $score, $total);
        $stmt->execute();
        $stmt->close();
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
