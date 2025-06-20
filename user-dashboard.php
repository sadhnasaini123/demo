<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Get user information
$userId = $_SESSION['user_id'];
$user = getUserById($userId);

// Get referrer information if exists
$referrerInfo = null;
if (!empty($user['referred_by'])) {
    $referrerInfo = getUserByReferenceCode($user['referred_by']);
}

// Get user's referrals
$referrals = getReferralsByUserId($userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - MLM System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f4f6f9;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .dashboard-header {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .welcome-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .welcome-text h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stat-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .stat-card h3 {
            color: #333;
            margin-bottom: 10px;
        }

        .stat-card p {
            font-size: 24px;
            color: #4e73df;
            font-weight: bold;
        }

        .referral-section {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        .referral-section h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .referral-code {
            background: #f8f9fc;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .referral-code p {
            margin-bottom: 10px;
            color: #666;
        }

        .referral-code code {
            background: #4e73df;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 16px;
        }

        .copy-btn {
            background: #4e73df;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }

        .copy-btn:hover {
            background: #2e59d9;
        }

        .referral-list {
            margin-top: 20px;
        }

        .referral-list table {
            width: 100%;
            border-collapse: collapse;
        }

        .referral-list th,
        .referral-list td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e3e6f0;
        }

        .referral-list th {
            background: #f8f9fc;
            color: #333;
        }

        .logout-btn {
            background: #e74a3b;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: #d52a1a;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="dashboard-header">
            <div class="welcome-section">
                <div class="welcome-text">
                    <h1>Welcome, <?php echo htmlspecialchars($user['email']); ?></h1>
                    <p>Your Referral Code: <strong><?php echo htmlspecialchars($user['reference_code']); ?></strong></p>
                </div>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Referrals</h3>
                <p><?php echo count($referrals); ?></p>
            </div>
            <div class="stat-card">
                <h3>Active Referrals</h3>
                <p><?php echo count(array_filter($referrals, function($r) { return $r['status'] === 'active'; })); ?></p>
            </div>
            <div class="stat-card">
                <h3>Account Status</h3>
                <p><?php echo ucfirst($user['status']); ?></p>
            </div>
        </div>

        <div class="referral-section">
            <h2>Your Referral Information</h2>
            <div class="referral-code">
                <p>Share this code to invite others:</p>
                <code id="referralCode"><?php echo htmlspecialchars($user['reference_code']); ?></code>
                <button onclick="copyReferralCode()" class="copy-btn">Copy Code</button>
            </div>

            <?php if (!empty($referrals)): ?>
                <div class="referral-list">
                    <h3>Your Referrals</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Join Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($referrals as $referral): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($referral['email']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($referral['created_at'])); ?></td>
                                    <td><?php echo ucfirst($referral['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>You haven't referred anyone yet. Share your referral code to get started!</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function copyReferralCode() {
            const referralCode = document.getElementById('referralCode');
            const textArea = document.createElement('textarea');
            textArea.value = referralCode.textContent;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            
            alert('Referral code copied to clipboard!');
        }
    </script>
</body>
</html>
