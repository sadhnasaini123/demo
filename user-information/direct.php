<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CryptoTech Mining Dashboard</title>
    <style>
        :root {
            --primary: #6c5ce7;
            --primary-light: #a29bfe;
            --primary-dark: #5649c0;
            --secondary: #00cec9;
            --accent: #fd79a8;
            --dark: #2d3436;
            --darker: #1e272e;
            --light: #f5f6fa;
            --success: #00b894;
            --warning: #fdcb6e;
            --danger: #d63031;
            --info: #0984e3;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--darker), #2c3e50);
            color: var(--light);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            height: 100vh;
            background: rgba(30, 39, 46, 0.9);
            backdrop-filter: blur(10px);
            position: fixed;
            left: -280px;
            top: 0;
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            box-shadow: 5px 0 30px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            overflow-y: auto;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar.open {
            left: 0;
        }
        
        .sidebar-header {
            padding: 20px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-header img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 15px;
        }
        
        .sidebar-header h3 {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--light);
        }
        
        .sidebar ul {
            list-style: none;
            padding: 15px 0;
        }
        
        .sidebar ul li {
            position: relative;
        }
        
        .sidebar ul li a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar ul li a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 3px;
            height: 100%;
            background: var(--primary);
            transform: scaleY(0);
            transform-origin: bottom;
            transition: transform 0.3s ease;
        }
        
        .sidebar ul li a:hover::before,
        .sidebar ul li a.active::before {
            transform: scaleY(1);
        }
        
        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background: rgba(108, 92, 231, 0.1);
            color: var(--light);
            padding-left: 30px;
        }
        
        .sidebar ul li a i {
            font-size: 1.1rem;
            margin-right: 15px;
            width: 20px;
            text-align: center;
            color: var(--primary-light);
        }
        
        .sidebar ul li a.active i {
            color: var(--primary);
        }
        
        .sidebar ul li a .menu-text {
            flex: 1;
        }
        
        .sidebar ul li a .arrow {
            transition: transform 0.3s ease;
        }
        
        .sidebar ul li a.open .arrow {
            transform: rotate(90deg);
        }
        
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            background: rgba(0, 0, 0, 0.2);
        }
        
        .submenu.open {
            max-height: 500px;
        }
        
        .submenu a {
            padding-left: 65px !important;
            font-size: 0.9rem;
            position: relative;
        }
        
        .submenu a::before {
            content: '';
            position: absolute;
            left: 45px;
            top: 50%;
            width: 8px;
            height: 8px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translateY(-50%);
        }
        
        .submenu a:hover::before {
            background: var(--primary);
        }
        
        /* Main Content Styles */
        .main-content {
            padding: 20px;
            transition: margin-left 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            min-height: 100vh;
        }
        
        .sidebar.open + .main-content {
            margin-left: 280px;
        }
        
        /* Toggle Button */
        .toggle-btn {
            position: fixed;
            left: 0px;
            right:20px;
            top: 20px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1001;
            box-shadow: 0 5px 15px rgba(108, 92, 231, 0.4);
            transition: all 0.3s ease;
            border: none;
            outline: none;
            font-size: 1.5rem;
        }
        
        .toggle-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 20px rgba(108, 92, 231, 0.6);
        }
        
        .toggle-btn.open {
            left: 200px;
            /* background: linear-gradient(135deg, var(--danger), #c0392b); */
        }
        
        /* Dashboard Header */
        .dashboard-header {
            background: rgba(45, 52, 54, 0.7);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            animation: fadeIn 0.8s ease;
        }
        
        .dashboard-header h2 {
            font-size: 1.8rem;
            margin-bottom: 10px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }
        
        .welcome-text {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        
        /* Stats Container */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
            margin-top: 25px;
        }
        
        .stat-card {
            background: rgba(45, 52, 54, 0.7);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary);
            animation: slideUp 0.5s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card:nth-child(2) {
            border-left-color: var(--secondary);
        }
        
        .stat-card:nth-child(3) {
            border-left-color: var(--accent);
        }
        
        .stat-card:nth-child(4) {
            border-left-color: var(--success);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05), transparent);
            z-index: -1;
        }
        
        .stat-card h3 {
            font-size: 0.9rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 10px;
        }
        
        .stat-card .value {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
            background: linear-gradient(to right, var(--light), #dfe6e9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .stat-card .change {
            font-size: 0.8rem;
            display: flex;
            align-items: center;
        }
        
        .stat-card .change.positive {
            color: var(--success);
        }
        
        .stat-card .change.negative {
            color: var(--danger);
        }
        
        /* Mining Status */
        .mining-status {
            background: rgba(45, 52, 54, 0.7);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 15px;
            margin-top: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            animation: fadeIn 1s ease;
        }
        
        .mining-status-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .mining-status-header h3 {
            font-size: 1.3rem;
            color: var(--light);
        }
        
        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            background: var(--success);
            color: white;
        }
        
        .status-badge.inactive {
            background: var(--danger);
        }
        
        .progress-container {
            margin-top: 20px;
        }
        
        .progress-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }
        
        .progress-bar {
            height: 10px;
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            width: 65%;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 5px;
            position: relative;
            animation: progressAnimation 2s ease-in-out infinite;
        }
        
        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: shimmer 2s infinite;
        }
        
        /* Recent Activity */
        .recent-activity {
            background: rgba(45, 52, 54, 0.7);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 15px;
            margin-top: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            animation: fadeIn 1.2s ease;
        }
        
        .recent-activity h3 {
            font-size: 1.3rem;
            margin-bottom: 20px;
            color: var(--light);
        }
        
        .activity-list {
            list-style: none;
        }
        
        .activity-item {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(108, 92, 231, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary);
            font-size: 1rem;
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-title {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .activity-time {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.5);
        }
        
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .action-card {
            background: rgba(45, 52, 54, 0.7);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, 0.1);
            animation: slideUp 0.7s ease;
        }
        
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            background: rgba(108, 92, 231, 0.2);
        }
        
        .action-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            color: white;
            font-size: 1.2rem;
        }
        
        .action-title {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .action-desc {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(20px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes progressAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .sidebar.open + .main-content {
                margin-left: 0;
            }
            
            .toggle-btn.open {
                left: 20px;
                background: linear-gradient(135deg, var(--danger), #c0392b);
            }
            
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 576px) {
            .quick-actions {
                grid-template-columns: 1fr;
            }
        }
        /* Mining Summary Styles */
.mining-summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

.summary-card {
    background: rgba(45, 52, 54, 0.7);
    backdrop-filter: blur(10px);
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border-left: 4px solid var(--primary);
    position: relative;
    overflow: hidden;
}

body.light-mode .summary-card {
    background: rgba(245, 247, 250, 0.7);
}

.summary-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.05), transparent);
    z-index: -1;
}

body.light-mode .summary-card::before {
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.05), transparent);
}

.summary-title {
    font-size: 1.3rem;
    margin-bottom: 20px;
    color: var(--primary-light);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding-bottom: 10px;
}

body.light-mode .summary-title {
    color: var(--primary-dark);
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

/* Mining Machine Styles */
.mining-machine h4, 
.backscenes-setup h4,
.levels-title,
.portfolio-title {
    font-size: 1.1rem;
    margin-bottom: 15px;
    color: var(--secondary);
}

.machine-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 20px;
}

.detail-item {
    display: flex;
    flex-direction: column;
}

.detail-label {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 5px;
}

body.light-mode .detail-label {
    color: rgba(45, 52, 54, 0.7);
}

.detail-value {
    font-size: 1.1rem;
    font-weight: 600;
}

/* Balance Styles */
.balance-item {
    margin-bottom: 15px;
}

.balance-amount {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 5px;
    background: linear-gradient(to right, var(--light), #dfe6e9);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

body.light-mode .balance-amount {
    background: linear-gradient(to right, var(--darker), #2d3436);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.balance-label {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.7);
}

body.light-mode .balance-label {
    color: rgba(45, 52, 54, 0.7);
}

/* Table Styles */
.table-container {
    overflow-x: auto;
}

.levels-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.levels-table th, 
.levels-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

body.light-mode .levels-table th,
body.light-mode .levels-table td {
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.levels-table th {
    font-weight: 600;
    color: var(--primary-light);
}

body.light-mode .levels-table th {
    color: var(--primary-dark);
}

.levels-table td.highlight {
    color: var(--accent);
    font-weight: 600;
}

/* Portfolio Grid */
.portfolio-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.portfolio-item {
    background: rgba(0, 0, 0, 0.2);
    padding: 15px;
    border-radius: 8px;
    text-align: center;
}

body.light-mode .portfolio-item {
    background: rgba(0, 0, 0, 0.05);
}

.portfolio-label {
    font-size: 0.9rem;
    font-weight: 500;
    margin-bottom: 5px;
}

.portfolio-sub {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 8px;
}

body.light-mode .portfolio-sub {
    color: rgba(45, 52, 54, 0.6);
}

.portfolio-value {
    font-size: 1.2rem;
    font-weight: 700;
}

/* External Link */
.external-link {
    margin-top: 20px;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.7);
}

body.light-mode .external-link {
    color: rgba(45, 52, 54, 0.7);
}

.external-link a {
    color: var(--primary-light);
    text-decoration: none;
    transition: color 0.2s;
}

body.light-mode .external-link a {
    color: var(--primary-dark);
}

.external-link a:hover {
    color: var(--accent);
    text-decoration: underline;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .mining-summary {
        grid-template-columns: 1fr;
    }
    
    .portfolio-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .machine-details {
        grid-template-columns: 1fr;
    }
    
    .portfolio-grid {
        grid-template-columns: 1fr;
    }
}

    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <button class="toggle-btn" id="toggleBtn">
        <i class="fas fa-bars"></i>
    </button>
    
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="https://ui-avatars.com/api/?name=Sadhna&background=6c5ce7&color=fff" alt="User">
            <h3>Sadhna</h3>
        </div>
        <ul>
            <li>
                <a href="#" class="active">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#" class="menu-item" onclick="toggleSubmenu(this)">
                    <i class="fas fa-user-circle"></i>
                    <span class="menu-text">My Account</span>
                    <span class="arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="submenu">
                    <a href="#"><i class="fas fa-user"></i>View/Edit Profile</a>
                    <a href="#"><i class="fas fa-cog"></i>Password Settings</a>
                
                </div>
            </li>
            <li>
                <a href="#" class="menu-item" onclick="toggleSubmenu(this)">
                    <i class="fas fa-microchip"></i>
                    <span class="menu-text">Mining Account</span>
                    <span class="arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="submenu">
                    <a href="#"><i class="fas fa-chart-line"></i> Mining Stats</a>
                    <a href="#"><i class="fas fa-box-open"></i> Mining Plans</a>
                    <a href="#"><i class="fas fa-history"></i> Mining History</a>
                </div>
            </li>
            <li>
                <a href="#" class="menu-item" onclick="toggleSubmenu(this)">
                    <i class="fas fa-wallet"></i>
                    <span class="menu-text">My Wallets</span>
                    <span class="arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="submenu">
                    <a href="#"><i class="fab fa-bitcoin"></i> BTC Wallet</a>
                    <a href="#"><i class="fab fa-ethereum"></i> ETH Wallet</a>
                    <a href="#"><i class="fas fa-coins"></i> USDT Wallet</a>
                </div>
            </li>
            <li>
                <a href="#" class="menu-item" onclick="toggleSubmenu(this)">
                    <i class="fas fa-user-plus"></i>
                    <span class="menu-text">New Registration</span>
                    <span class="arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="submenu">
                    <a href="#"><i class="fas fa-user-edit"></i> Register Member</a>
                    <a href="#"><i class="fas fa-list-alt"></i> Registration History</a>
                </div>
            </li>
            <li>
                <a href="#" class="menu-item" onclick="toggleSubmenu(this)">
                    <i class="fas fa-users"></i>
                    <span class="menu-text">My Team</span>
                    <span class="arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="submenu">
                     <a href="direct.php"><i class="fas fa-network-wired"></i>Direct Level</a>
                    <a href="#"><i class="fas fa-network-wired"></i>Levels</a>
                </div>
            </li>
            <li>
                <a href="#" class="menu-item" onclick="toggleSubmenu(this)">
                    <i class="fas fa-chart-pie"></i>
                    <span class="menu-text">Reports</span>
                    <span class="arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="submenu">
                    <a href="#"><i class="fas fa-calendar-day"></i> Daily Report</a>
                    <a href="#"><i class="fas fa-calendar-week"></i> Weekly Report</a>
                    <a href="#"><i class="fas fa-calendar-alt"></i> Monthly Report</a>
                </div>
            </li>
            <li>
                <a href="#" class="menu-item" onclick="toggleSubmenu(this)">
                    <i class="fas fa-money-bill-wave"></i>
                    <span class="menu-text">Withdrawals</span>
                    <span class="arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="submenu">
                    <a href="#"><i class="fas fa-hand-holding-usd"></i> Request Withdrawal</a>
                    <a href="#"><i class="fas fa-file-invoice-dollar"></i> Withdrawal History</a>
                </div>
            </li>
            <li>
                <a href="#" class="menu-item" onclick="toggleSubmenu(this)">
                    <i class="fas fa-headset"></i>
                    <span class="menu-text">Support</span>
                    <span class="arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="submenu">
                    <a href="#"><i class="fas fa-ticket-alt"></i> New Ticket</a>
                    <a href="#"><i class="fas fa-tasks"></i> My Tickets</a>
                    <a href="#"><i class="fas fa-question-circle"></i> FAQs</a>
                </div>
            </li>
            <li>
                <a href="#" class="menu-item" onclick="toggleSubmenu(this)">
                    <i class="fas fa-newspaper"></i>
                    <span class="menu-text">News & Updates</span>
                    <span class="arrow"><i class="fas fa-chevron-right"></i></span>
                </a>
                <div class="submenu">
                    <a href="#"><i class="fas fa-bullhorn"></i> Announcements</a>
                    <a href="#"><i class="fas fa-sync-alt"></i> Updates</a>
                </div>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="menu-text">Logout</span>
                </a>
            </li>
        </ul>
    </div>
    
    <div class="main-content">
        <div class="dashboard-header">     
       
         <!-- add the miidle content side of the sidebar -->
          <h3 style="margin-top:10px;">Direct Team</h3>
          <div style="width:10%;height:2px;background-color:white;"></div>
          <table class="levels-table">
    <thead>
        <tr>
            <th>Sno</th>
            <th>Name</th>
            <th>Email</th>
            <th>Account ID</th>
            <th>Status</th>
            <th>JoinDate</th>

        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>John Doe</td>
            <td>john.doe@example.com</td>
            <td>ACC001</td>
            <td class="highlight">Active</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Jane Smith</td>
            <td>jane.smith@example.com</td>
            <td>ACC002</td>
            <td class="highlight">Inactive</td>
        </tr>
        <tr>
            <td>3</td>
            <td>Alice Brown</td>
            <td>alice.brown@example.com</td>
            <td>ACC003</td>
            <td class="highlight">Pending</td>
        </tr>
    </tbody>
</table>
        </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleBtn');
        
        function toggleSidebar() {
            sidebar.classList.toggle('open');
            toggleBtn.classList.toggle('open');
            
            // Change icon based on state
            const icon = toggleBtn.querySelector('i');
            if (sidebar.classList.contains('open')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        }
        
        function toggleSubmenu(element) {
            // Close all other submenus first
            const allMenuItems = document.querySelectorAll('.menu-item');
            allMenuItems.forEach(item => {
                if (item !== element) {
                    item.classList.remove('open');
                    item.nextElementSibling.classList.remove('open');
                }
            });
            
            // Toggle current submenu
            element.classList.toggle('open');
            const submenu = element.nextElementSibling;
            submenu.classList.toggle('open');
            
            // Close sidebar on mobile when a menu item is clicked
            if (window.innerWidth <= 992) {
                const submenuLinks = submenu.querySelectorAll('a');
                submenuLinks.forEach(link => {
                    link.addEventListener('click', () => {
                        sidebar.classList.remove('open');
                        toggleBtn.classList.remove('open');
                        const icon = toggleBtn.querySelector('i');
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    });
                });
            }
        }
        
        toggleBtn.addEventListener('click', toggleSidebar);
        
        // Close sidebar when clicking outside
        document.addEventListener('click', function(event) {
            if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                if (sidebar.classList.contains('open') && window.innerWidth <= 992) {
                    toggleSidebar();
                }
            }
        });
        
        // Add animation delays to stat cards
        document.addEventListener('DOMContentLoaded', function() {
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
            
            const actionCards = document.querySelectorAll('.action-card');
            actionCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1 + 0.3}s`;
            });
        });
    </script>
</body>
</html>