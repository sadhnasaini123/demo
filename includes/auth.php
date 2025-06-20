<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/mlm/includes/functions.php';

// session_start();


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

function registerUser($username, $email, $password, $referenceCode = null, $accountId) {
    global $pdo;
    
    // Generate unique reference code if empty
    if (empty($referenceCode)) {
        $referenceCode = generateUniqueReferenceCode();
    }
    
    try {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, reference_code, account_id) 
                              VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$username, $email, $hashedPassword, $referenceCode, $accountId]);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Registration error: " . $e->getMessage());
        return false;
    }
}

function generateUniqueReferenceCode() {
    global $pdo;
    $code = '';
    $exists = true;
    
    // Keep generating until we get a unique code
    while ($exists) {
        $code = 'REF' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 7));
        $stmt = $pdo->prepare("SELECT id FROM users WHERE reference_code = ?");
        $stmt->execute([$code]);
        $exists = (bool)$stmt->fetch();
    }
    
    return $code;
}
// Helper functions
function generateAccountId() {
    // Example: Generate a consistent unique ID
    return 'MLM' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
    
    // Or if you want sequential IDs:
    // $lastId = getLastUserIdFromDatabase(); // Implement this
    // return 'MLM' . str_pad($lastId + 1, 6, '0', STR_PAD_LEFT);
}
function emailExists($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch() !== false;
}

function updateDownlineCount($userId) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET downline_count = downline_count + 1 WHERE id = ?");
    $stmt->execute([$userId]);
}

function isAdmin() {
    if (!isLoggedIn()) {
        return false;
    }
    return $_SESSION['user_email'] === 'sadhnasaini085@gmail.com';
}
?>
<?php
require_once __DIR__ . '/config.php';
// includes/auth.php

function loginUser($accountId, $password) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE account_id = ?");
        $stmt->execute([$accountId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        return false;
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserByEmail($email) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Get user by email error: " . $e->getMessage());
        return false;
    }
}