<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

// if (!isLoggedIn()) {
//     header('Location: login.php');
//     exit;
// }

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


$allactiveuser=getAllactiveUser();
$allInactiveuser=getAllInactiveuser();

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLM Pro Dashboard</title>
    <link rel="stylesheet" href="css/status-button.css">
    <script src="js/status.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #1cc88a;
            --dark-color: #5a5c69;
            --light-color: #f8f9fc;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 80px;
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Nunito', sans-serif;
        }
        
        body {
            display: flex;
            background-color: #f8f9fc;
            overflow-x: hidden;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, #224abe 100%);
            color: white;
            transition: var(--transition);
            position: fixed;
            z-index: 1000;
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }
        
        .sidebar-header {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            white-space: nowrap;
        }
        
        .logo .short {
            display: none;
        }
        
        .sidebar.collapsed .logo .full {
            display: none;
        }
        
        .sidebar.collapsed .logo .short {
            display: block;
            font-size: 2rem;
        }
        
        .toggle-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .sidebar.collapsed .toggle-btn {
            transform: rotate(180deg);
        }
        
        .sidebar-menu {
            padding: 1rem 0;
            overflow-y: auto;
            height: calc(100vh - 70px);
        }
        
        .menu-item {
            position: relative;
        }
        
        .menu-item a {
            display: flex;
            align-items: center;
            padding: 0.8rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
            white-space: nowrap;
        }
        
        .menu-item a:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .menu-item.active > a {
            color: white;
            background: rgba(255, 255, 255, 0.2);
        }
        
        .menu-item i {
            margin-right: 0.8rem;
            font-size: 1.1rem;
            min-width: 20px;
        }
        
        .sidebar.collapsed .menu-item span {
            display: none;
        }
        
        .sidebar.collapsed .menu-item i {
            margin-right: 0;
            font-size: 1.3rem;
        }
        
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: rgba(0, 0, 0, 0.1);
        }
        
        .submenu.show {
            max-height: 500px;
        }
        
        .submenu a {
            padding-left: 3rem;
            font-size: 0.9rem;
        }
        
        .sidebar.collapsed .submenu a {
            padding-left: 1.5rem;
        }
        
        .menu-item.has-submenu > a::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: auto;
            transition: var(--transition);
            font-size: 0.8rem;
        }
        
        .menu-item.has-submenu.active > a::after {
            transform: rotate(180deg);
        }
        
        .sidebar.collapsed .menu-item.has-submenu > a::after {
            display: none;
        }
        
        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: var(--transition);
            min-height: 100vh;
        }
        
        .sidebar.collapsed ~ .main-content {
            margin-left: var(--sidebar-collapsed-width);
        }
        
        .topbar {
            height: 70px;
            background: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .search-bar {
            display: flex;
            align-items: center;
            background: var(--light-color);
            border-radius: 20px;
            padding: 0.3rem 1rem;
            width: 300px;
        }
        
        .search-bar input {
            border: none;
            background: transparent;
            padding: 0.5rem;
            width: 100%;
            outline: none;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
        }
        
        .user-menu .notification {
            position: relative;
            margin-right: 1.5rem;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--secondary-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 0.8rem;
            object-fit: cover;
        }
        
        .user-name {
            font-weight: 600;
            margin-right: 0.5rem;
        }
        
        .content-area {
            padding: 2rem;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .page-title h1 {
            font-size: 1.8rem;
            color: var(--dark-color);
            font-weight: 700;
        }
        
        .breadcrumb {
            display: flex;
            list-style: none;
        }
        
        .breadcrumb li {
            margin-right: 0.5rem;
            color: var(--dark-color);
        }
        
        .breadcrumb li:after {
            content: '/';
            margin-left: 0.5rem;
            color: #d1d3e2;
        }
        
        .breadcrumb li:last-child:after {
            content: '';
        }
        
        .breadcrumb li a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .card {
            background: white;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            overflow: hidden;
        }
        
        .card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e3e6f0;
            background: #f8f9fc;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .stat-card {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            color: white;
            border-radius: 0.35rem;
        }
        
        .stat-card.primary {
            background: var(--primary-color);
        }
        
        .stat-card.success {
            background: var(--secondary-color);
        }
        
        .stat-card.warning {
            background: #f6c23e;
        }
        
        .stat-card.danger {
            background: #e74a3b;
        }
        
        .stat-icon {
            font-size: 2rem;
            margin-right: 1rem;
            opacity: 0.7;
        }
        
        .stat-info h3 {
            font-size: 1.5rem;
            margin-bottom: 0.2rem;
        }
        
        .stat-info p {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        /* Status button styles */
        .status-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.3s;
        }
        
        .status-btn.active {
            background-color: #e74a3b;
            color: white;
        }
        
        .status-btn.inactive {
            background-color: #1cc88a;
            color: white;
        }
        
        .status-btn:hover {
            opacity: 0.8;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e3e6f0;
        }
        
        th {
            background-color: #f8f9fc;
            font-weight: 600;
        }
        
        tr:hover {
            background-color: #f8f9fc;
        }
        
        /* Tab content styling */
        .tab-content {
            display: none;
            padding: 20px;
            background: white;
            border-radius: 0 0 5px 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .tab-content.active {
            display: block;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            border-radius: 5px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .modal-footer {
            margin-top: 20px;
            text-align: right;
        }

        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }

        .btn-primary {
            background-color: #4e73df;
            color: white;
        }

        .btn-secondary {
            background-color: #858796;
            color: white;
        }

        .btn-danger {
            background-color: #e74a3b;
            color: white;
        }

        .action-icons {
            display: flex;
            gap: 10px;
        }

        .action-icons i {
            cursor: pointer;
        }

    .action-icons i.fa-trash-alt {
            color: #e74a3b;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-menu-btn {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <span class="full">MLM Pro</span>
                <span class="short">MP</span>
            </div>
            <button class="toggle-btn">
                <i class="fas fa-angle-left"></i>
            </button>
        </div>
        
        <div class="sidebar-menu">
            <!-- Dashboard -->
            <div class="menu-item active">
                <a href="#" data-tab="dashboard">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <!-- Network -->
            <div class="menu-item has-submenu">
                <a href="#" data-tab="network">
                    <i class="fas fa-fw fa-network-wired"></i>
                    <span>Network</span>
                </a>
                <div class="submenu show">
                    <div class="menu-item">
                        <a href="#" data-tab="genealogy-tree">Genealogy Tree</a>
                    </div>
                    <div class="menu-item">
                        <a href="#" data-tab="downline-members">Downline Members</a>
                    </div>
                    <div class="menu-item">
                        <a href="#" data-tab="referral-tracking">Referral Tracking</a>
                    </div>
                    <div class="menu-item">
                        <a href="#" data-tab="sponsor-finder">Sponsor Finder</a>
                    </div>
                </div>
            </div>
            
            <!-- Members -->
            <div class="menu-item has-submenu">
                <a href="#">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Members</span>
                </a>
                <div class="submenu">
                    <div class="menu-item">
                        <a href="#" data-tab="member-search">Search</a>
                    </div>
                    <div class="menu-item">
                        <a href="#" data-tab="active-members">Active</a>
                    </div>
                    <div class="menu-item">
                        <a href="#" data-tab="inactive-members">In-active</a>
                    </div>
                </div>
            </div>
            
            <!-- Commissions -->
            <div class="menu-item has-submenu">
                <a href="#" data-tab="commissions">
                    <i class="fas fa-fw fa-money-bill-wave"></i>
                    <span>Commissions</span>
                </a>
                <div class="submenu">
                    <div class="menu-item">
                        <a href="#" data-tab="commission-report">Commission Report</a>
                    </div>
                    <div class="menu-item">
                        <a href="#" data-tab="payout-history">Payout History</a>
                    </div>
                    <div class="menu-item">
                        <a href="#" data-tab="bonus-calculator">Bonus Calculator</a>
                    </div>
                    <div class="menu-item">
                        <a href="#" data-tab="withdrawal-requests">Withdrawal Requests</a>
                    </div>
                </div>
            </div>
            
            <!-- Withdrawal -->
            <div class="menu-item has-submenu">
                <a href="#" data-tab="withdrawal">
                    <i class="fas fa-fw fa-boxes"></i>
                    <span>Withdrawal</span>
                </a>
                <div class="submenu">
                    <div class="menu-item">
                        <a href="#" data-tab="pending-withdrawal">Pending Withdrawal</a>
                    </div>
                    <div class="menu-item">
                        <a href="#" data-tab="process-withdrawal">Process Withdrawal</a>
                    </div>
                    <div class="menu-item">
                        <a href="#" data-tab="complete-withdrawal">Complete Withdrawal</a>
                    </div>
                    <div class="menu-item">
                        <a href="#" data-tab="direct-withdrawal">Direct Withdrawal</a>
                    </div>
                </div>
            </div>
            
            <!-- Reports -->
            <div class="menu-item has-submenu">
                <a href="#" data-tab="reports">
                    <i class="fas fa-fw fa-chart-bar"></i>
                    <span>Reports</span>
                </a>
                <div class="submenu">
                    <div class="menu-item">
                        <a href="#" data-tab="performance-report">Performance Report</a>
                    </div>
                    <div class="menu-item">
                        <a href="#" data-tab="team-growth">Team Growth</a>
                    </div>
                    <div class="menu-item">
                        <a href="#" data-tab="commission-summary">Commission Summary</a>
                    </div>
                    <div class="menu-item">
                        <a href="#" data-tab="rank-advancement">Rank Advancement</a>
                    </div>
                </div>
            </div>
            
            <!-- Settings -->
            <div class="menu-item has-submenu">
                <a href="#" data-tab="settings">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Settings</span>
                </a>
                <div class="submenu">
                    <div class="menu-item">
                        <a href="#" data-tab="mlm-configuration">MLM Configuration</a>
                    </div>
                    <div class="menu-item">
                        <a href="#" data-tab="commission-plans">Commission Plans</a>
                    </div>
                    <div class="menu-item">
                        <a href="#" data-tab="rank-settings">Rank Settings</a>
                    </div>
                    <div class="menu-item">
                        <a href="#" data-tab="system-settings">System Settings</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search...">
            </div>
            
            <div class="user-menu">
                <div class="notification">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">5</span>
                </div>
                
                <div class="user-profile">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="user-avatar">
                    <span class="user-name">John Doe</span>
                    <i class="fas fa-angle-down"></i>
                </div>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="content-area">
            <div class="page-header">
                <div class="page-title">
                    <h1 id="current-tab-title">Dashboard</h1>
                </div>
                
                <ul class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li id="current-breadcrumb">Dashboard</li>
                </ul>
            </div>
            
            <!-- Tab Contents -->
            <div id="dashboard" class="tab-content active">
                <!-- Stats Cards -->
                <div class="stats-cards">
                    <div class="stat-card primary">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo  count($allUsers)?></h3>
                            <p>Total Members</p>
                        </div>
                    </div>
                    
                    <div class="stat-card success">
                        <div class="stat-icon">
                            <i class="fas fa-network-wired"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo count($allactiveuser)?></h3>
                            <p>Active-Member</p>
                        </div>
                    </div>
                    
                    <div class="stat-card success" style="background: #ffd6e7">
                        <div class="stat-icon">
                            <i class="fas fa-network-wired"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo count($allInactiveuser)?></h3>
                            <p>Inactive-Member</p>
                        </div>
                    </div>
                    
                    <div class="stat-card warning" style="background: #e83e8c">
                        <div class="stat-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="stat-info">
                            <h3>$24,567</h3>
                            <p>Panding Payout</p>
                        </div>
                    </div>
                    
                    <div class="stat-card danger">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-info">
                            <h3>487</h3>
                            <p>Today TopUp</p>
                        </div>
                    </div>
                    
                    <div class="stat-card danger" style="background: #1cc88a">
                        <div class="stat-icon">
                            <i class="fa-regular fa-file-signature"></i>
                        </div>
                        <div class="stat-info">
                            <h3>487</h3>
                            <p>Today joining</p>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activities Table -->
                <div class="row">
                    <div class="card">
                        <div class="card-header">
                            <h5>Recent Activities</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($allUsers)): ?>
                                <?php if (isset($error)): ?>
                                    <div class="alert alert-danger"><?php echo $error; ?></div>
                                <?php endif; ?>
                                
                                <table>
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Email</th>
                                            <th>Referral Code</th>
                                            <th>Referred By</th>
                                            <th>Joining</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sno = 1; foreach ($allUsers as $u): ?>
                                            <tr>
                                                <td><?php echo $sno++; ?></td>
                                                <td><?php echo htmlspecialchars($u['email']); ?></td>
                                                <td><?php echo htmlspecialchars($u['reference_code']); ?></td>
                                                 <td>
                            <?php 
                                if (!empty($u['referrer_username'])) {
                                    echo htmlspecialchars($u['referrer_username']);
                                } else {
                                    echo 'None';
                                }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($u['joining_date']);?></td>
                                                <td style="color: <?php echo $u['status'] === 'active' ? 'green' : 'red'; ?>">
                                                    <?php echo ucfirst($u['status']); ?>
                                                </td>                                                <td>
                                                    <div class="action-icons">
                                                        <i class="fas fa-trash-alt" onclick="deleteUser(<?php echo $u['id']; ?>)"></i><button 
                                                            class="status-btn <?php echo $u['status'] === 'active' ? 'active' : 'inactive'; ?>"
                                                            onclick="handleStatusToggle(this, <?php echo $u['id']; ?>, '<?php echo $u['status']; ?>')"
                                                        >
                                                            <?php echo $u['status'] === 'active' ? 'Deactivate' : 'Activate'; ?>
                                                        </button>
                                                    </div>
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
                </div>
            </div>
            
            <!-- Network Tab Contents -->
            <div id="network" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h5>Network Overview</h5>
                    </div>
                    <div class="card-body">
                        <p>This is the main network dashboard where you can view your entire network structure.</p>
                    </div>
                </div>
            </div>
            
            <div id="genealogy-tree" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h5>Genealogy Tree</h5>
                    </div>
                    <div class="card-body">
                        <p>Visual representation of your network hierarchy showing all levels and connections.</p>
                    </div>
                </div>
            </div>
            
            <div id="downline-members" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h5>Downline Members</h5>
                    </div>
                    <div class="card-body">
                        <p>Detailed list of all members in your downline with their performance metrics.</p>
                    </div>
                </div>
            </div>
            
            <!-- Members Tab Contents -->
            <!-- <div id="members" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h5>Members Management</h5>
                    </div>
                    <div class="card-body">
                        <p>Central hub for managing all member accounts and profiles.</p>
                    </div>
                </div>
            </div> -->
            
            <div id="member-search" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h5>Member Search</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($allUsers)): ?>
            <table>
                <thead>
                    <tr>
                    
                        <th>S.NO</th>
                        <th>Email</th>
                        <th>Referral Code</th>
                        <th>Referred By</th>
                        <th>joining</th>
                        <th>Status</th>
                        <th>Toggle</th>
                    </tr>
                </thead>
                <tbody>
                <?php $sno = 1; foreach ($allUsers as $u): ?>
                    <tr class="<?php echo $u['status']; ?>">
                    
                     <td><?php echo $sno++; ?></td>
                      <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo htmlspecialchars($u['reference_code']); ?></td>
                        <td>
                            <?php 
                                if (!empty($u['referrer_username'])) {
                                    echo htmlspecialchars($u['referrer_username']);
                                } else {
                                    echo 'None';
                                }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($u['joining_date'])?></td>
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
            </div>
            
            <!-- Add similar divs for all other tabs -->
             <div id="member-search" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h5>Member Search</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($allUsers)): ?>
            <table>
                <thead>
                    <tr>
                    
                        <th>S.No</th>
                        <th>Email</th>
                        <th>Referral Code</th>
                        <th>Referred By</th>
                        <th>Status</th>
                        <th>Toggle</th>
                    </tr>
                </thead>
                <tbody>          
                <?php $sno = 1; foreach ($allUsers as $u): ?>
                    <tr class="<?php echo $u['status']; ?>">
                     <td><?php echo $sno++; ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo htmlspecialchars($u['reference_code']); ?></td>
                        <td>
                            <?php
                                if (!empty($u['referrer_username'])) {
                                    echo htmlspecialchars($u['referrer_username']);
                                } else {
                                    echo 'None';
                                }
                            ?>
                        </td>
                        <td style="color:<?php echo $u['status'] === 'active' ? 'green' : 'red'; ?>">
                            <?php echo ucfirst($u['status']); ?>
                        </td>
                        <td>
                            <form method="post" style="margin:0;">
                                <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                <input type="hidden" name="toggle_status" value="<?php echo $u['status']; ?>">
                                <!-- <button type="submit"
                                    class="status-btn <?php echo $u['status'] === 'active' ? 'active' : 'inactive'; ?>">
                                    <?php echo $u['status'] === 'active' ? 'Set Inactive' : 'Set Active'; ?>
                                </button> -->
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
            </div>
            <div id="active-members" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h5>Active Members</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($allUsers)): ?>
                            </div>
            <table>
                <thead>
                    <tr>
                    
                        <th>S.No</th>
                        <th>Email</th>
                        <th>Referral Code</th>
                        <th>Referred By</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>          
                <?php $sno= 1;foreach ($allactiveuser as $u): ?>
                    <tr class="<?php echo $u['status']; ?>">
                        <td><?php echo $sno++; ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo htmlspecialchars($u['reference_code']); ?></td>
                        <td>
                            <?php
                                if (!empty($u['referrer_username'])) {
                                    echo htmlspecialchars($u['referrer_username']);
                                } else {
                                    echo 'None';
                                }
                            ?>
                        </td>
                        <td style="color:<?php echo $u['status'] === 'active' ? 'green' : 'red'; ?>">
                            <?php echo ucfirst($u['status']); ?>
                        </td>
                         <td>
                            <div style="display: flex; gap: 1.5rem;">
                            <!-- <i class="fa-solid fa-pen-to-square"></i> -->
                            <i class="fa-solid fa-trash"></i>
                            </div>
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
                
            <div id="inactive-members" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h5>In-Active Members</h5>
                        
                    </div>
                    <div class="card-body">
                        <?php if (!empty($allUsers)): ?>
                       
            <table>
                <thead>
                    <tr>
                    
                        <th>S.No</th>
                        <th>Email</th>
                        <th>Referral Code</th>
                        <th>Referred By</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>          
                <?php  $sno=1; foreach  ($allInactiveuser as $u): ?>
                    <tr class="<?php echo $u['status']; ?>">
                        <td><?php echo $sno++; ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo htmlspecialchars($u['reference_code']); ?></td>
                        <td>
                            <?php
                                if (!empty($u['referrer_username'])) {
                                    echo htmlspecialchars($u['referrer_username']);
                                } else {
                                    echo 'None';
                                }
                            ?>
                        </td>
                        <td style="color:<?php echo $u['status'] === 'active' ? 'green' : 'red'; ?>">
                            <?php echo ucfirst($u['status']); ?>
                        </td>
                        <td>
                            <div style="display: flex; gap: 1.5rem;">
                            <!-- <i class="fa-solid fa-pen-to-square"></i> -->
                            <i class="fa-solid fa-trash"></i>
                            </div>
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

            <!-- Commission tabs -->
            <div id="commissions" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h5>Commissions Dashboard</h5>
                    </div>
                    <div class="card-body">
                        <p>Overview of all commission-related activities and earnings.</p>
                    </div>
                </div>
            </div>
        
            <!-- Withdrawal tabs -->
            <div id="withdrawal" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h5>Withdrawal Management</h5>
                    </div>
                    <div class="card-body">
                        <p>Manage all withdrawal requests and transactions.</p>
                    </div>
                </div>
            </div>
            
            <!-- Reports tabs -->
            <div id="reports" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h5>Reports Center</h5>
                    </div>
                    <div class="card-body">
                        <p>Generate and view various business reports and analytics.</p>
                    </div>
                </div>
            </div>
            
            <!-- Settings tabs -->
            <div id="settings" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <h5>System Settings</h5>
                    </div>
                    <div class="card-body">
                        <p>Configure system-wide settings and preferences.</p>
                    </div>
                </div>
            </div>
            
            <!-- Add all other tab contents similarly -->
            <!-- For brevity, I'm not including all of them, but you should have one for each data-tab -->
        </div>
    </div>
      <!-- Modal section removed -->

    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('editUserModal');
        const closeBtn = modal.querySelector('.close');
        const closeBtnSecondary = modal.querySelector('.close-btn');
        const editForm = document.getElementById('editUserForm');

        // Close modal when clicking the X or Cancel button
        closeBtn.onclick = function() {
            modal.style.display = "none";
        }
        closeBtnSecondary.onclick = function() {
            modal.style.display = "none";
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }        // Edit functionality removed

        // Handle delete functionality
        window.deleteUser = function(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch('ajax/delete_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: userId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('User deleted successfully!');
                        location.reload(); // Reload the page to show updated data
                    } else {
                        alert('Error deleting user: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error deleting user: ' + error);
                });
            }
        }
    });
    </script>
    <script>
        // Toggle Sidebar
        document.querySelector('.toggle-btn').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        });
        
        // Toggle Submenus
        document.querySelectorAll('.has-submenu > a').forEach(item => {
            item.addEventListener('click', function(e) {
                if(!document.querySelector('.sidebar').classList.contains('collapsed')) {
                    e.preventDefault();
                    const submenu = this.nextElementSibling;
                    const parentItem = this.parentElement;
                    
                    // Close other open submenus in the same level
                    const siblings = parentItem.parentElement.querySelectorAll('.has-submenu');
                    siblings.forEach(sibling => {
                        if(sibling !== parentItem) {
                            sibling.classList.remove('active');
                            sibling.querySelector('.submenu').classList.remove('show');
                        }
                    });
                    
                    // Toggle current submenu
                    parentItem.classList.toggle('active');
                    submenu.classList.toggle('show');
                }
            });
        });
        
        // Tab switching functionality
        document.querySelectorAll('.sidebar-menu a[data-tab]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const tabId = this.getAttribute('data-tab');
                
                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(tab => {
                    tab.classList.remove('active');
                });
                
                // Show selected tab content
                const tabContent = document.getElementById(tabId);
                if (tabContent) {
                    tabContent.classList.add('active');
                }
                
                // Update page title and breadcrumb
                const tabTitle = this.textContent.trim();
                document.getElementById('current-tab-title').textContent = tabTitle;
                document.getElementById('current-breadcrumb').textContent = tabTitle;
                
                // Close sidebar if collapsed
                if (document.querySelector('.sidebar').classList.contains('collapsed')) {
                    document.querySelector('.sidebar').classList.remove('collapsed');
                }
            });
        });
        
        // Auto-close submenus when sidebar is collapsed
        const sidebar = document.querySelector('.sidebar');
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if(mutation.attributeName === 'class') {
                    if(sidebar.classList.contains('collapsed')) {
                        document.querySelectorAll('.submenu').forEach(submenu => {
                            submenu.classList.remove('show');
                        });
                        document.querySelectorAll('.has-submenu').forEach(item => {
                            item.classList.remove('active');
                        });
                    }
                }
            });
        });
        
        observer.observe(sidebar, {
            attributes: true
        });
    </script>
    <script>
        // Handle status toggle        window.toggleStatus = function(userId, currentStatus, button) {
            // Show loading state
            button.disabled = true;
            button.innerHTML = 'Updating...';
            
            const formData = new FormData();
            formData.append('user_id', userId);
            formData.append('current_status', currentStatus);

            fetch('ajax/update_status.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())            .then(data => {
                button.disabled = false;
                
                if (data.success) {
                    // Update button text and class
                    button.textContent = data.button_text;
                    button.className = 'status-btn ' + data.new_status;
                    
                    // Update status text color in the status column
                    const statusCell = button.closest('tr').querySelector('td[style*="color"]');
                    if (statusCell) {
                        statusCell.style.color = data.new_status === 'active' ? 'green' : 'red';
                        statusCell.textContent = data.new_status.charAt(0).toUpperCase() + data.new_status.slice(1);
                    }
                    
                    // Update the onclick handler with the new status
                    button.setAttribute('onclick', `toggleStatus(${userId}, '${data.new_status}', this)`);
                } else {
                    alert('Error updating status: ' + data.message);
                    // Reset button text to original state
                    button.textContent = currentStatus === 'active' ? 'Deactivate' : 'Activate';
                }
            })
            .catch(error => {
                alert('Error updating status: ' + error);
            });
        }
    </script>
    <script>
        function handleStatusToggle(button, userId, currentStatus) {
            // Disable button while processing
            button.disabled = true;
            button.innerHTML = 'Processing...';

            // Determine new status
            const newStatus = currentStatus === 'active' ? 'inactive' : 'active';

            // Create form data
            const formData = new FormData();
            formData.append('user_id', userId);
            formData.append('status', newStatus);

            // Send request
            fetch('ajax/toggle_status.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button state
                    button.className = 'status-btn ' + data.new_status;
                    button.textContent = data.button_text;
                    
                    // Update status cell color and text
                    const row = button.closest('tr');
                    const statusCell = row.querySelector('td[style*="color"]');
                    if (statusCell) {
                        statusCell.style.color = data.new_status === 'active' ? 'green' : 'red';
                        statusCell.textContent = data.new_status.charAt(0).toUpperCase() + data.new_status.slice(1);
                    }

                    // Update onclick handler with new status
                    button.setAttribute('onclick', `handleStatusToggle(this, ${userId}, '${data.new_status}')`);
                } else {
                    alert('Error: ' + data.message);
                    // Reset button state
                    button.textContent = currentStatus === 'active' ? 'Deactivate' : 'Activate';
                }
                // Re-enable button
                button.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating status. Please try again.');
                button.disabled = false;
                button.textContent = currentStatus === 'active' ? 'Deactivate' : 'Activate';
            });
        }
    </script>
</body>
</html>