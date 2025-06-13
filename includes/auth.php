<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/mlm/includes/functions.php';

session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function login($email, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['reference_code'] = $user['reference_code'];
        $_SESSION['user_email'] = $user['email']; // <-- Add this line
        return true;
    }
    
    return false;
}

function logout() {
    session_unset();
    session_destroy();
}

function registerUser($username, $email, $password, $referenceCode ) {
    global $pdo;

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $userReferenceCode = generateReferenceCode();

    $sponsorId = null; // <-- Add this line

    try {
        $pdo->beginTransaction();

        // Determine sponsor level
        if ($referenceCode) {
            $sponsor = getUserByReferenceCode($referenceCode);
            if ($sponsor) {
                $sponsorId = $sponsor['id'];
            }
        }

        // Insert user with sponsor
        $stmt = $pdo->prepare("
            INSERT INTO users (username, email, password, reference_code, referred_by) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $username, 
            $email, 
            $hashedPassword, 
            $userReferenceCode, 
            $sponsorId
        ]);

        $userId = $pdo->lastInsertId();

        $pdo->commit();
        return $userId;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

function isAdmin() {
    if (!isLoggedIn()) {
        return false;
    }
    return $_SESSION['user_email'] === 'sadhnasaini085@gmail.com';
}
?>