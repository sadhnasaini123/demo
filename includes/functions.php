<?php

// This should point to database.php correctly
require_once __DIR__ . '/../config/database.php';
// Rest of your functions...

function generateReferenceCode() {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i = 0; $i < 8; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $code;
}

function addUserToUsers($email, $password, $referredBy , $referred_by) {
    global $pdo;
    $referenceCode = generateReferenceCode();
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // If referredBy is set, check if it exists
    if ($referredBy) {
        $referrer = getUserByReferenceCode($referredBy);
        if (!$referrer) {
            return ['success' => false, 'message' => 'Invalid referral code.'];
        }
    }

    $stmt = $pdo->prepare("INSERT INTO users (email, password, reference_code, referred_by, note) VALUES (?, ?, ?, ?, ?)");
    $result = $stmt->execute([$email, $hashedPassword, $referenceCode, $referredBy, $note]);
    if ($result) {
        return ['success' => true, 'reference_code' => $referenceCode];
    } else {
        return ['success' => false, 'message' => 'Registration failed.'];
    }
}
// function getDownlineCount($userId) {
//     global $pdo;
//     $stmt = $pdo->prepare("SELECT COUNT(*) FROM tree WHERE parent_id = ?");
//     $stmt->execute([$userId]);
//     return $stmt->fetchColumn();
// }

// function getDownlineUsers($userId) {
//     global $pdo;
//     $stmt = $pdo->prepare("
//         SELECT u.*, t.level, t.position 
//         FROM users u 
//         JOIN tree t ON u.id = t.user_id 
//         WHERE t.parent_id = ?
//         ORDER BY t.position
//     ");
//     $stmt->execute([$userId]);
//     return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }

// function getUserTree($userId, $maxLevel = 5) {
//     global $pdo;
    
//     $tree = [];
//     $userStmt = $pdo->prepare("
//         SELECT u.*, t.level, t.position 
//         FROM users u 
//         JOIN tree t ON u.id = t.user_id 
//         WHERE u.id = ?
//     ");
//     $userStmt->execute([$userId]);
//     $user = $userStmt->fetch(PDO::FETCH_ASSOC);
    
//     if ($user) {
//         $tree['user'] = $user;
//         $tree['downlines'] = [];
        
//         if ($user['level'] < $maxLevel) {
//             $downlines = getDownlineUsers($userId);
//             foreach ($downlines as $downline) {
//                 $tree['downlines'][] = getUserTree($downline['id'], $maxLevel);
//             }
//         }
//     }
    
//     return $tree;
// }

function getUserByReferenceCode($referenceCode) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE reference_code = ?");
    $stmt->execute([$referenceCode]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function getUserById($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
function getReferralsByUserId($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE referred_by = (SELECT reference_code FROM users WHERE id = ?)");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getAllUsers() {
    global $pdo; // Assuming you're using PDO
    
     $stmt = $pdo->prepare("
        SELECT u.*, 
               ref.username as referrer_username,
               ref.id as referrer_id 
        FROM users u 
        LEFT JOIN users ref ON ref.id = u.referred_by 
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getAllactiveUser() {
    global $pdo;
    // $stmt = $pdo->prepare("SELECT * FROM users WHERE status = 'active'");
     $stmt = $pdo->prepare("
        SELECT u.*, 
               ref.username as referrer_username,
               ref.id as referrer_id 
        FROM users u 
        LEFT JOIN users ref ON ref.id = u.referred_by 
         WHERE u.status = 'active'
        
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllInactiveuser() {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT u.*, 
               ref.username as referrer_username,
               ref.id as referrer_id 
        FROM users u 
        LEFT JOIN users ref ON ref.id = u.referred_by 
        WHERE u.status = 'inactive'
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateUserStatus($userId, $status) {
    global $pdo;
    
    try {
        // Update the status directly
        $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
        $success = $stmt->execute([$status, $userId]);
        
        if ($success) {
            return [
                'success' => true,
                'new_status' => $status,
                'button_text' => ($status === 'active') ? 'Deactivate' : 'Activate',
                'message' => 'User status updated successfully'
            ];
        } else {
            return ['success' => false, 'message' => 'Failed to update status'];
        }
    } catch (PDOException $e) {
        error_log("Error updating user status: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}
?>