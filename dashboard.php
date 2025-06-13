<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

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

$downlineStmt = $pdo->prepare("
    SELECT username, status
    FROM users 
    WHERE referred_by = ?
");
$downlineStmt->execute([$userId]);
$downlines = $downlineStmt->fetchAll(PDO::FETCH_ASSOC);

// Count direct referrals
$directReferralsCount = count($downlines);

// Calculate wallet balance (replace with actual database query)
$walletBalance = 0.00; // Placeholder, replace with actual logic to fetch wallet balance
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        :root {
            --primary: #5A55AE;
            --primary-light: #8676D5;
            --success: #28a745;
            --danger: #dc3545;
            --background: #f4f6f8;
            --card-bg: #ffffff;
            --text-dark: #2e2e2e;
            --muted: #6c757d;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease; /* Define a transition variable */
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--background);
        }

        .container {
            max-width: 960px;
            margin: 50px auto;
            padding: 20px;
        }

        .profile-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            padding: 30px;
            border-radius: 12px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow);
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .profile-header:hover {
            transform: translateY(-5px);
        }

        .profile-pic {
            width: 100px;
            height: 100px;
            background-color: #e0e0e0;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
            border: 3px solid #fff;
        }

        .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-info {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .info-box {
            background: var(--card-bg);
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: var(--shadow);
            transition: var(--transition); /* Use the transition variable */
        }

        .info-box h4 {
            margin: 0 0 10px;
            color: var(--muted);
            font-weight: normal;
        }

        .info-box p {
            margin: 0;
            font-weight: bold;
            color: var(--text-dark);
        }

        .info-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .dashboard-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            background-color: #fff;
            color: var(--primary);
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s ease;
            border: 1px solid var(--primary);
        }

        .btn:hover {
            background-color: var(--primary);
            color: #fff;
        }

        .referred-users {
            margin-top: 40px;
            background: var(--card-bg);
            padding: 20px;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        .referred-users h2 {
            margin-bottom: 20px;
            color: var(--text-dark);
        }

        .referral-table {
            width: 100%;
            border-collapse: collapse;
        }

        .referral-table th,
        .referral-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        .referral-table tr.active {
            background-color: #d4edda;
        }

        .referral-table tr.inactive {
            background-color: #f8d7da;
        }

        .referral-table tr:hover {
            background-color: #f1f1f1;
        }

        .no-referrals {
            padding: 20px;
            text-align: center;
            color: var(--muted);
            background: #f1f1f1;
            border-radius: 8px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-header" onclick="window.location.href='edit-profile.php'">
            <div class="profile-pic <?= $user['status'] === 'active' ? 'active' : 'inactive'; ?>">
                <?php if (!empty($user['profile_pic']) && file_exists($user['profile_pic'])): ?>
                    <img src="<?= htmlspecialchars($user['profile_pic']) ?>" alt="Profile Picture">
                <?php else: ?>
                    <?= strtoupper(substr($user['username'], 0, 1)) ?>
                <?php endif; ?>
            </div>
            <div style="flex-grow:1; padding-left: 20px;">
                <h1><?= htmlspecialchars($user['username']); ?></h1>
                <p><?= htmlspecialchars($user['email']); ?></p>
            </div>
            <div class="dashboard-actions">
                <?php if (isAdmin()): ?>
                    <a href="network.php" class="btn">Admin Panel</a>
                <?php endif; ?>
                <a href="logout.php" class="btn">Logout</a>
            </div>
        </div>

        <div class="profile-info">
            <div class="info-box">
                <h4>Reference Code</h4>
                <p><?= htmlspecialchars($user['reference_code']); ?></p>
            </div>
            <div class="info-box">
                <h4>Referred By</h4>
                <p><?= htmlspecialchars($user['referrer_username']); ?></p>
            </div>
        </div>

         <div class="profile-info">
            <div class="info-box">
                <h4>Wallet Balance</h4>
                <p>$<?= htmlspecialchars($walletBalance); ?></p>
            </div>
            <div class="info-box">
                <h4>Direct Referrals</h4>
                <p><?= htmlspecialchars($directReferralsCount); ?></p>
            </div>
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
                            <tr class="<?= $downline['status'] === 'active' ? 'active' : 'inactive'; ?>">
                                <td><?= htmlspecialchars($downline['username']); ?></td>
                                <td><?= ucfirst($downline['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-referrals">No users have used your ID as referral yet.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
