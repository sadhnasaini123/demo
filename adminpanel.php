<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLM Pro Dashboard</title>
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
                <a href="#">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <!-- Network -->
            <div class="menu-item has-submenu">
                <a href="#">
                    <i class="fas fa-fw fa-network-wired"></i>
                    <span>Network</span>
                </a>
                <div class="submenu show">
                    <div class="menu-item">
                        <a href="#">Genealogy Tree</a>
                    </div>
                    <div class="menu-item">
                        <a href="#">Downline Members</a>
                    </div>
                    <div class="menu-item">
                        <a href="#">Referral Tracking</a>
                    </div>
                    <div class="menu-item">
                        <a href="#">Sponsor Finder</a>
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
                        <a href="#">Search</a>
                    </div>
                    <div class="menu-item">
                        <a href="#">Active</a>
                    </div>
                    <div class="menu-item">
                        <a href="#">In-active</a>
                    </div>
                </div>
            </div>
            
            <!-- Commissions -->
            <div class="menu-item has-submenu">
                <a href="#">
                    <i class="fas fa-fw fa-money-bill-wave"></i>
                    <span>Commissions</span>
                </a>
                <div class="submenu">
                    <div class="menu-item">
                        <a href="#">Commission Report</a>
                    </div>
                    <div class="menu-item">
                        <a href="#">Payout History</a>
                    </div>
                    <div class="menu-item">
                        <a href="#">Bonus Calculator</a>
                    </div>
                    <div class="menu-item">
                        <a href="#">Withdrawal Requests</a>
                    </div>
                </div>
            </div>
            
            <!-- Products -->
            <div class="menu-item has-submenu">
                <a href="#">
                    <i class="fas fa-fw fa-boxes"></i>
                    <span>withdrwal</span>
                </a>
                <div class="submenu">
                    <div class="menu-item">
                        <a href="#">Pending withdrwal</a>
                    </div>
                    <div class="menu-item">
                        <a href="#">Process withdrwal</a>
                    </div>
                    <div class="menu-item">
                        <a href="#">Complete withdrwal</a>
                    </div>
                    <div class="menu-item">
                        <a href="#">Direct withdrwal</a>
                    </div>
                </div>
            </div>
            
            
            <!-- Reports -->
            <div class="menu-item has-submenu">
                <a href="#">
                    <i class="fas fa-fw fa-chart-bar"></i>
                    <span>Reports</span>
                </a>
                <div class="submenu">
                    <div class="menu-item">
                        <a href="#">Performance Report</a>
                    </div>
                    <div class="menu-item">
                        <a href="#">Team Growth</a>
                    </div>
                    <div class="menu-item">
                        <a href="#">Commission Summary</a>
                    </div>
                    <div class="menu-item">
                        <a href="#">Rank Advancement</a>
                    </div>
                </div>
            </div>
            
            <!-- Settings -->
            <div class="menu-item has-submenu">
                <a href="#">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Settings</span>
                </a>
                <div class="submenu">
                    <div class="menu-item">
                        <a href="#">MLM Configuration</a>
                    </div>
                    <div class="menu-item">
                        <a href="#">Commission Plans</a>
                    </div>
                    <div class="menu-item">
                        <a href="#">Rank Settings</a>
                    </div>
                    <div class="menu-item">
                        <a href="#">System Settings</a>
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
                    <h1>Dashboard</h1>
                </div>
                
                <ul class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li>Dashboard</li>
                </ul>
            </div>
            
            <!-- Stats Cards -->
            <div class="stats-cards">
                <div class="stat-card primary">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>1,254</h3>
                        <p>Total Members</p>
                    </div>
                </div>
                
                <div class="stat-card success">
                    <div class="stat-icon">
                        <i class="fas fa-network-wired"></i>
                    </div>
                    <div class="stat-info">
                        <h3>3,452</h3>
                        <p>Active-Member</p>
                    </div>
                </div>
                    <div class="stat-card success" style="background: #ffd6e7">
                    <div class="stat-icon">
                        <i class="fas fa-network-wired"></i>
                    </div>
                    <div class="stat-info">
                        <h3>3,252</h3>
                        <p>Inactive-Member</p>
                    </div>
                </div>
                
                <div class="stat-card warning" style="background: #e83e8c">
                    <div class="stat-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-info" >
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
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-info">
                        <h3>487</h3>
                        <p>Today joining</p>
                    </div>
                </div>
            </div>
            
            <!-- Main Content Row -->
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <h5>Recent Activities</h5>
                    </div>
                    <div class="card-body">
                        <!-- Activity content goes here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    
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
</body>
</html>