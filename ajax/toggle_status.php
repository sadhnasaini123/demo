<?php
require_once '../includes/functions.php';
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
    $status = isset($_POST['status']) ? $_POST['status'] : null;

    if (!$userId || !$status) {
        echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
        exit;
    }

    // Validate status value
    if (!in_array($status, ['active', 'inactive'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid status value']);
        exit;
    }

    $result = updateUserStatus($userId, $status);
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
