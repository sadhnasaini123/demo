<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

if (!isAdmin()) {
    header('Location: dashboard.php');
    exit;
}

$userId = $_SESSION['user_id'];
$user = getUserById($userId);
$referrerEmail = null;
if ($user && $user['referred_by']) {
    $referrer = getUserByReferenceCode($user['referred_by']);
    if ($referrer) {
        $referrerEmail = $referrer['email'];
    }
}

// Get users referred by the current user
$referrals = getReferralsByUserId($userId);
$allUsers = getAllUsers();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_status'], $_POST['user_id'])) {
    $toggleUserId = intval($_POST['user_id']);
    $newStatus = $_POST['toggle_status'] === 'active' ? 'inactive' : 'active';
    updateUserStatus($toggleUserId, $newStatus);
    header("Location: network.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Your Referral Info</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #bbb;
            text-align: left;
        }
        th{
            background:rgb(213, 223, 158);
        }
        tr.active {
            background: #d4ffd4;
        }
        tr.inactive {
            background: #ffd4d4;
        }
        .status-btn {
            padding: 5px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: #fff;
        }
        .status-btn.active {
            background: #ff4d4d;
        }
        .status-btn.inactive {
            background: #4dff4d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>All Registered Users</h1>
        <a href="dashboard.php" class="btn">Back to Dashboard</a>
            <a href="logout.php" class="btn">Logout</a>

        <div class="all-users">
            
            <?php if (!empty($allUsers)): ?>
            <table>
                <thead>
                    <tr>
                    
                        <th>user_id</th>
                        <th>Email</th>
                        <th>Referral Code</th>
                        <th>Referred By</th>
                        <th>Status</th>
                        <th>Toggle</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($allUsers as $u): ?>
                    <tr class="<?php echo $u['status']; ?>">
                    
                        <td><?php echo htmlspecialchars($u['id']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo htmlspecialchars($u['reference_code']); ?></td>
                        <td>
                            <?php echo !empty($u['referred_by']) ? htmlspecialchars($u['referred_by']) : 'self'; ?>
                        </td>
                        <td style="color:<?php echo $u['status'] === 'active' ? 'green' : 'red'; ?>">
                            <?php echo ucfirst($u['status']); ?>
                        </td>
                        <td>
                            <form method="post" style="margin:0;">
                                <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                <input type="hidden" name="toggle_status" value="<?php echo $u['status']; ?>">
                                <button type="submit"
                                    class="status-btn <?php echo $u['status'] === 'active' ? 'active' : 'inactive'; ?>">
                                    <?php echo $u['status'] === 'active' ? 'Set Inactive' : 'Set Active'; ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p>No users found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>