<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'] ?? null;

    if ($userId) {
        $result = updateUserStatus($userId);
        echo json_encode($result);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Missing user ID'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>
