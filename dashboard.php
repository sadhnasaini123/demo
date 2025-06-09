<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php'; // ensure $pdo is defined

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
// Modified query to get referrer's username using user_id from referred_by
$userStmt = $pdo->prepare("
    SELECT u1.*, 
           CASE 
               WHEN u1.referred_by IS NOT NULL THEN (
                   SELECT username 
                   FROM users 
                   WHERE id = u1.referred_by
               )
               ELSE 'None'
           END as referrer_username
    FROM users u1 
    WHERE u1.id = ?
");
$userStmt->execute([$userId]);
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

// Update the query to check referred_by against user ID instead of reference_code
$downlineStmt = $pdo->prepare("
    SELECT username, status
    FROM users 
    WHERE referred_by = ?
");
$downlineStmt->execute([$userId]);  // Using userId instead of reference_code
$downlines = $downlineStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #6c757d;
            transition: all 0.3s ease;
        }
        .profile-pic.active {
            border: 3px solid rgb(134, 243, 143);
            box-shadow: 0 0 15px rgba(134, 243, 143, 0.5);
        }
        .profile-pic.inactive {
            border: 3px solid rgb(252, 133, 151);
            box-shadow: 0 0 15px rgba(252, 133, 151, 0.5);
        }
        .profile-name {
            flex-grow: 1;
        }
        .profile-name h1 {
            margin: 0;
            font-size: 24px;
        }
        .profile-name p {
            margin: 5px 0 0;
            color: #6c757d;
        }
        .profile-info { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 20px; }
        .info-box { padding: 15px; background: #f1f1f1; border-radius: 5px; }
        .info-box h4 { margin: 0 0 5px; font-size: 14px; color: #555; }
        .info-box p { margin: 0; font-size: 16px; color: #000; }
        .info-box.active { background:rgb(134, 243, 143); ; border-radius: 5px; }
        .info-box.inactive { background:rgb(252, 133, 151);border-radius: 5px; }
        .dashboard-actions { 
            margin-top: 30px; 
            text-align: right;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        .btn { padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        .btn:hover { background-color: #0056b3; }
        .btn.logout {
            background-color: #0056b3;
        }
        .btn.logout:hover {
            background-color: #0056b3;
        }
        .referred-users {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .referred-users h2 {
            color: #333;
            margin-bottom: 20px;
        }
            .referral-summary {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .referral-summary p {
            margin: 5px 0;
        }
        .downline-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }
        .downline-box {
            padding: 15px;
            border-radius: 5px;
            background: #f1f1f1;
            border: 1px solid #ddd;
            transition: transform 0.2s;
        }
        .downline-box:hover {
            transform: translateY(-2px);
        }
        .downline-info {
            text-align: left;
        }
        .join-date {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .no-referrals {
            text-align: center;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .referral-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .referral-table th, .referral-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .referral-table tr.active {
            background-color: rgb(134, 243, 143);
            box-shadow: 0 0 10px rgba(134, 243, 143, 0.5);
            border: 1px solid #6ed17e;
            margin-bottom: 10px;
        }
        .referral-table tr.inactive {
            background-color: rgb(252, 133, 151);
            box-shadow: 0 0 10px rgba(252, 133, 151, 0.5);
            border: 1px solid #ff8095;
        }
        .referral-table tr {
            transition: all 0.3s ease;
            position: relative;
        }
        .referral-table tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-header">
            <div class="profile-pic <?php echo $user['status'] == 'active' ? 'active' : 'inactive'; ?>">
                <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
            </div>
            <div class="profile-name">
                <h1><?php echo htmlspecialchars($user['username']); ?></h1>
                <p><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
            <div class="dashboard-actions">
                <?php if (isAdmin()): ?>
                    <a href="network.php" class="btn">Admin Panel</a>
                <?php endif; ?>
                <a href="logout.php" class="btn logout">Logout</a>
            </div>
        </div>

        <div class="profile-info">
            <div class="info-box">
                <h4>Reference Code</h4>
                <p><?php echo htmlspecialchars($user['reference_code']); ?></p>
            </div>
            <div class="info-box">
                <h4>Referred By</h4>
                <p><?php echo htmlspecialchars($user['referrer_username']); ?></p>
            </div>
            <!-- <div class="info-box">
                <h4>Email</h4>
                <p><?php echo htmlspecialchars($user['email']); ?></p>
            </div> -->
            <!-- <div class="info-box <?php echo $user['status'] == 'active' ? 'active' : 'inactive'; ?>">
                <h4>Status</h4>
                <p><?php echo ucfirst($user['status']); ?></p>
            </div> -->
         
        </div>

        <div class="referred-users">
            <h2>Users Who Used My ID as Referral</h2>
            <?php if (count($downlines) > 0): ?>
                <table class="referral-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($downlines as $downline): ?>
                            <tr class="<?php echo $downline['status'] == 'active' ? 'active' : 'inactive'; ?>">
                                <td><?php echo htmlspecialchars($downline['username']); ?></td>
                                <td><?php echo ucfirst($downline['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No users have used your ID as referral yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
