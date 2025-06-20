<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLM Pro - Premium User Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.css">
    <style>
        :root {
            --primary: #6e45e2;
            --primary-light: #8c6beb;
            --secondary:rgb(32, 50, 53);
            --accent: #2dd4bf;
            --accent-dark: #1cb4a0;
            --light: #f8f9fa;
            --dark: #1a1a2e;
            --dark-light: #2a2a3a;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gradient-primary: linear-gradient(135deg, #6e45e2 0%, #4a2d8c 100%);
            --gradient-accent: linear-gradient(135deg, #2dd4bf 0%, #6e45e2 100%);
            --gradient-light: linear-gradient(135deg, #f8f9fa 0%, #e8ecf5 100%);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: var(--dark);
            overflow-x: hidden;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header */
        header {
            background: white;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            padding: 20px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        header.scrolled {
            padding: 15px 0;
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 26px;
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            transition: all 0.3s ease;
        }
        
        .logo:hover {
            transform: scale(1.02);
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 8px 15px;
            border-radius: 30px;
        }
        
        .user-menu:hover {
            background: rgba(110, 69, 226, 0.1);
        }
        
        .user-menu img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            border: 2px solid var(--primary);
            transition: all 0.3s ease;
        }
        
        .user-menu:hover img {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(110, 69, 226, 0.3);
        }
        
        /* Hero Section */
        .hero {
            padding: 120px 0 100px;
            text-align: center;
            background: var(--gradient-light);
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(110, 69, 226, 0.1) 0%, rgba(255,255,255,0) 70%);
            z-index: 0;
            animation: pulse 15s infinite alternate;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
        }
        
        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            color: var(--dark);
            line-height: 1.2;
            animation: fadeInUp 0.8s ease;
        }
        
        .hero h1 span {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .hero p {
            font-size: 20px;
            color: #666;
            max-width: 700px;
            margin: 0 auto 40px;
            animation: fadeInUp 0.8s ease 0.2s both;
        }
        
        .cta-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            animation: fadeInUp 0.8s ease 0.4s both;
        }
        
        .btn {
            padding: 14px 35px;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.4s;
            text-decoration: none;
            display: inline-block;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient-primary);
            z-index: -1;
            opacity: 0;
            transition: all 0.4s;
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            border: none;
            box-shadow: 0 5px 15px rgba(110, 69, 226, 0.3);
        }
        
        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(110, 69, 226, 0.4);
        }
        
        .btn-outline:hover {
            background: rgba(110, 69, 226, 0.1);
            color: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(110, 69, 226, 0.2);
        }
        
        .btn-outline::before {
            background: var(--gradient-primary);
        }
        
        .btn:hover::before {
            opacity: 1;
        }
        
        /* Stats Section */
        .stats {
            padding: 60px 0;
            background: white;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.4s;
            text-align: center;
            border: 1px solid rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .stat-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--gradient-primary);
            transition: all 0.4s;
        }
        
        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        
        .stat-card:hover::after {
            height: 10px;
        }
        
        .stat-icon {
            font-size: 40px;
            margin-bottom: 20px;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .stat-value {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 10px;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .stat-label {
            color: #666;
            font-size: 16px;
        }
        
        /* Features Section */
        .features {
            padding: 100px 0;
            background: var(--gradient-light);
            position: relative;
        }
        
        .features::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: white;
            clip-path: polygon(0 80%, 100% 0, 100% 100%, 0% 100%);
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .section-title h2 {
            font-size: 40px;
            color: var(--dark);
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }
        
        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--gradient-primary);
            border-radius: 2px;
        }
        
        .section-title p {
            color: #666;
            font-size: 18px;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.4s;
            text-align: center;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient-primary);
            z-index: -1;
            opacity: 0;
            transition: all 0.4s;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            color: white;
        }
        
        .feature-card:hover::before {
            opacity: 1;
        }
        
        .feature-card:hover h3,
        .feature-card:hover p,
        .feature-card:hover .feature-icon {
            color: white;
        }
        
        .feature-icon {
            width: 90px;
            height: 90px;
            background: rgba(110, 69, 226, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            color: var(--primary);
            font-size: 36px;
            transition: all 0.4s;
        }
        
        .feature-card:hover .feature-icon {
            background: rgba(255,255,255,0.2);
        }
        
        .feature-card h3 {
            font-size: 24px;
            margin-bottom: 15px;
            transition: all 0.4s;
        }
        
        .feature-card p {
            color: #666;
            line-height: 1.6;
            transition: all 0.4s;
        }
        
        /* Network Preview */
        .network-preview {
            padding: 100px 0;
            background: white;
            position: relative;
        }
        
        .network-preview::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: var(--gradient-light);
            clip-path: polygon(0 0, 100% 0, 100% 100%, 0 80%);
        }
        
        .preview-content {
            display: flex;
            align-items: center;
            gap: 50px;
        }
        
        .preview-text {
            flex: 1;
        }
        
        .preview-text h2 {
            font-size: 36px;
            margin-bottom: 20px;
            color: var(--dark);
            line-height: 1.3;
        }
        
        .preview-text h2 span {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .preview-text p {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
            font-size: 17px;
        }
        
        .feature-list {
            margin-bottom: 30px;
        }
        
        .feature-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        
        .feature-icon-sm {
            width: 24px;
            height: 24px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            font-size: 12px;
            flex-shrink: 0;
        }
        
        .preview-visual {
            flex: 1;
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }
        
        .network-graph {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .graph-node {
            position: absolute;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border: 2px solid var(--primary);
            color: var(--primary);
            font-weight: bold;
            transition: all 0.3s;
            z-index: 2;
        }
        
        .graph-node:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(110, 69, 226, 0.3);
            z-index: 3;
        }
        
        .graph-node.main {
            width: 70px;
            height: 70px;
            background: var(--gradient-primary);
            color: white;
            border: none;
            font-size: 20px;
        }
        
        .graph-line {
            position: absolute;
            background: #ddd;
            height: 2px;
            transform-origin: left center;
            z-index: 1;
        }
        
        /* Analytics Section */
        .analytics {
            padding: 100px 0;
            background: var(--gradient-light);
        }
        
        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        
        .analytics-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.4s;
        }
        
        .analytics-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        
        .analytics-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .analytics-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--dark);
        }
        
        .analytics-value {
            font-size: 24px;
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }
        
        /* Testimonials */
        .testimonials {
            padding: 100px 0;
            background: white;
            position: relative;
        }
        
        .testimonials::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: var(--gradient-light);
            clip-path: polygon(0 0, 100% 0, 100% 100%, 0 80%);
        }
        
        .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        
        .testimonial-card {
            background: white;
            border-radius: 15px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.4s;
            position: relative;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .testimonial-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }
        
        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 80px;
            color: rgba(110, 69, 226, 0.1);
            font-family: serif;
            line-height: 1;
        }
        
        .testimonial-content {
            margin-bottom: 30px;
            color: #666;
            font-style: italic;
            line-height: 1.8;
            font-size: 16px;
            position: relative;
            z-index: 1;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
        }
        
        .testimonial-author img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 15px;
            object-fit: cover;
            border: 3px solid var(--primary-light);
        }
        
        .author-info h4 {
            font-size: 18px;
            margin-bottom: 5px;
            color: var(--dark);
        }
        
        .author-info p {
            color: var(--primary);
            font-size: 14px;
            font-weight: 500;
        }
        
        .rating {
            color: var(--warning);
            margin-top: 5px;
        }
        
        /* CTA Section */
        .cta-section {
            padding: 100px 0;
            background: var(--gradient-primary);
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .cta-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            z-index: 0;
            animation: pulse 15s infinite alternate;
        }
        
        .cta-content {
            position: relative;
            z-index: 1;
        }
        
        .cta-section h2 {
            font-size: 36px;
            margin-bottom: 20px;
            line-height: 1.3;
        }
        
        .cta-section p {
            font-size: 18px;
            max-width: 700px;
            margin: 0 auto 40px;
            opacity: 0.9;
        }
        
        .cta-buttons-2 .btn {
            margin: 0 10px;
        }
        
        .btn-white {
            background: white;
            color: var(--primary);
            font-weight: 600;
        }
        
        .btn-white:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255,255,255,0.3);
        }
        
        .btn-transparent {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        
        .btn-transparent:hover {
            background: rgba(255,255,255,0.1);
        }
        
        /* Footer */
        footer {
            background: var(--dark);
            color: white;
            padding: 100px 0 30px;
            position: relative;
        }
        
        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: var(--gradient-primary);
            clip-path: polygon(0 0, 100% 0, 100% 100%, 0 80%);
            opacity: 0.8;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-bottom: 60px;
            position: relative;
            z-index: 1;
        }
        
        .footer-column h3 {
            font-size: 20px;
            margin-bottom: 25px;
            color: var(--accent);
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-column h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background: var(--accent);
            border-radius: 3px;
        }
        
        .footer-column p {
            color: #ccc;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .footer-column ul {
            list-style: none;
        }
        
        .footer-column li {
            margin-bottom: 12px;
        }
        
        .footer-column a {
            color: #ccc;
            text-decoration: none;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }
        
        .footer-column a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .footer-column a:hover {
            color: white;
            transform: translateX(5px);
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            color: white;
            font-size: 18px;
            transition: all 0.3s;
        }
        
        .social-links a:hover {
            background: var(--accent);
            transform: translateY(-3px);
        }
        
        .copyright {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: #999;
            font-size: 14px;
            position: relative;
            z-index: 1;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .preview-content {
                flex-direction: column;
            }
            
            .preview-visual {
                margin-top: 40px;
                width: 100%;
            }
            
            .analytics-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 36px;
            }
            
            .section-title h2 {
                font-size: 32px;
            }
            
            .cta-buttons, .cta-buttons-2 {
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }
            
            .btn {
                width: 100%;
                max-width: 250px;
                text-align: center;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 576px) {
            .hero {
                padding: 80px 0;
            }
            
            .hero h1 {
                font-size: 28px;
            }
            
            .hero p {
                font-size: 16px;
            }
            
            .section-title h2 {
                font-size: 28px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .feature-card, .testimonial-card {
                padding: 30px 20px;
            }
            
            footer {
                padding: 60px 0 30px;
            }
        }
        /* Reset some default styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 2rem;
    background-color: #2c3e50;
    color: white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 100;
}

.logo {
    font-size: 1.8rem;
    font-weight: 700;
    color: #ecf0f1;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.logo:hover {
    color: #3498db;
    cursor: pointer;
    transition: color 0.3s ease;
}

.user-menu ul {
    display: flex;
    list-style: none;
    gap: 2rem;
}

.user-menu ul li {
    font-size: 1rem;
    font-weight: 500;
    text-transform: capitalize;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.user-menu ul li:hover {
    background-color: #3498db;
    color: white;
    transform: translateY(-2px);
}

/* Responsive design for smaller screens */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        padding: 1rem;
    }
    
    .user-menu ul {
        margin-top: 1rem;
        gap: 1rem;
    }
    
    .user-menu ul li {
        padding: 0.5rem;
        font-size: 0.9rem;
    }
}
    </style>
</head>
<body>
    <!-- Header -->
    <header id="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">MLM Pro</div>
                <div class="user-menu">
                    <ul>
                    <li>Home</li>
                    <li>About</li>
                    <li>services</li>
                    <li><a style="color:white;text-decoration:none;" href="signup.php">Register</a></li>
                    <li><a style="color:white;text-decoration:none;"  href="login.php">Login</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="container hero-content">
            <h1>Welcome to Your <span>MLM Business Portal</span></h1>
            <p>Powerful tools to grow your network, track your earnings, and achieve your financial goals. Everything you need to succeed in one place.</p>
            <div class="cta-buttons">
                <a href="#network" class="btn btn-primary">View My Network</a>
                <a href="#" class="btn btn-outline">Learn How It Works</a>
            </div>
        </div>
    </section>
    
    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-value">248</div>
                    <div class="stat-label">Team Members</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="stat-value">7</div>
                    <div class="stat-label">Network Levels</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="stat-value">$3,845</div>
                    <div class="stat-label">This Month Earnings</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stat-value">Gold</div>
                    <div class="stat-label">Your Current Rank</div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="section-title">
                <h2>Your Business Tools</h2>
                <p>Access all the features you need to manage and grow your MLM business effectively</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-network-wired"></i>
                    </div>
                    <h3>Network Management</h3>
                    <p>Visualize your entire downline, track growth, and manage your team with powerful genealogy tools.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Performance Analytics</h3>
                    <p>Real-time insights into your business performance with detailed reports and statistics.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h3>Earnings Tracker</h3>
                    <p>Monitor your commissions, bonuses, and payments with complete transparency.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Team Communication</h3>
                    <p>Built-in messaging and announcement system to keep your team informed and motivated.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-gift"></i>
                    </div>
                    <h3>Rewards System</h3>
                    <p>Track your progress toward rewards and incentives with our achievement system.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Mobile Ready</h3>
                    <p>Access your business anytime, anywhere with our fully responsive mobile interface.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Network Preview -->
    <section class="network-preview" id="network">
        <div class="container">
            <div class="preview-content">
                <div class="preview-text">
                    <h2>Your Network <span>at a Glance</span></h2>
                    <p>Our interactive network viewer lets you explore your downline, identify top performers, and discover growth opportunities. See exactly how your business is structured and where to focus your efforts.</p>
                    
                    <div class="feature-list">
                        <div class="feature-item">
                            <div class="feature-icon-sm">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>Real-time network visualization with drill-down capabilities</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon-sm">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>Performance indicators for each team member</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon-sm">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>Identify strongest branches and growth opportunities</div>
                        </div>
                    </div>
                    
                    <a href="#" class="btn btn-primary">Explore Full Network</a>
                </div>
                <div class="preview-visual">
                    <div class="network-graph">
                        <div class="graph-node main">YOU</div>
                        <div class="graph-node" style="top: 100px; left: 50px;">A1</div>
                        <div class="graph-node" style="top: 100px; left: 250px;">A2</div>
                        <div class="graph-node" style="top: 100px; left: 450px;">A3</div>
                        <div class="graph-node" style="top: 200px; left: 0px;">B1</div>
                        <div class="graph-node" style="top: 200px; left: 100px;">B2</div>
                        <div class="graph-node" style="top: 200px; left: 150px;">B3</div>
                        <div class="graph-node" style="top: 200px; left: 300px;">B4</div>
                        <div class="graph-node" style="top: 200px; left: 400px;">B5</div>
                        <div class="graph-node" style="top: 200px; left: 500px;">B6</div>
                        
                        <div class="graph-line" style="width: 100px; top: 85px; left: 185px; transform: rotate(-20deg);"></div>
                        <div class="graph-line" style="width: 100px; top: 85px; left: 285px; transform: rotate(0deg);"></div>
                        <div class="graph-line" style="width: 100px; top: 85px; left: 385px; transform: rotate(20deg);"></div>
                        
                        <div class="graph-line" style="width: 80px; top: 135px; left: 70px; transform: rotate(-30deg);"></div>
                        <div class="graph-line" style="width: 80px; top: 135px; left: 120px; transform: rotate(-10deg);"></div>
                        <div class="graph-line" style="width: 80px; top: 135px; left: 170px; transform: rotate(10deg);"></div>
                        <div class="graph-line" style="width: 80px; top: 135px; left: 320px; transform: rotate(-10deg);"></div>
                        <div class="graph-line" style="width: 80px; top: 135px; left: 420px; transform: rotate(-10deg);"></div>
                        <div class="graph-line" style="width: 80px; top: 135px; left: 520px; transform: rotate(10deg);"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Analytics Section -->
    <section class="analytics">
        <div class="container">
            <div class="section-title">
                <h2>Performance Analytics</h2>
                <p>Track your business growth with comprehensive analytics and reporting tools</p>
            </div>
            
            <div class="analytics-grid">
                <div class="analytics-card">
                    <div class="analytics-header">
                        <div class="analytics-title">Monthly Earnings</div>
                        <div class="analytics-value">$3,845</div>
                    </div>
                    <div class="chart-container">
                        <canvas id="earningsChart"></canvas>
                    </div>
                </div>
                
                <div class="analytics-card">
                    <div class="analytics-header">
                        <div class="analytics-title">Team Growth</div>
                        <div class="analytics-value">+24%</div>
                    </div>
                    <div class="chart-container">
                        <canvas id="growthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Testimonials -->
    <section class="testimonials">
        <div class="container">
            <div class="section-title">
                <h2>Success Stories</h2>
                <p>Hear from other entrepreneurs who transformed their businesses with our platform</p>
            </div>
            
            <div class="testimonial-grid">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        The network visualization tools helped me identify untapped potential in my team. Within 3 months, I doubled my monthly earnings by focusing on the right people. The analytics dashboard is incredibly intuitive and packed with actionable insights.
                    </div>
                    <div class="testimonial-author">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Sarah J.">
                        <div class="author-info">
                            <h4>Sarah Johnson</h4>
                            <p>Diamond Rank, since 2019</p>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        The real-time analytics transformed how I manage my team. I can now spot trends immediately and adjust my strategy accordingly. My productivity has never been higher. The mobile app keeps me connected wherever I go.
                    </div>
                    <div class="testimonial-author">
                        <img src="https://randomuser.me/api/portraits/men/68.jpg" alt="Michael T.">
                        <div class="author-info">
                            <h4>Michael Thompson</h4>
                            <p>Platinum Rank, since 2020</p>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        As someone who was new to MLM, the training resources and clear performance metrics gave me the confidence to build a successful business from scratch. The step-by-step guidance and community support were invaluable.
                    </div>
                    <div class="testimonial-author">
                        <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Lisa M.">
                        <div class="author-info">
                            <h4>Lisa Martinez</h4>
                            <p>Gold Rank, since 2021</p>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container cta-content">
            <h2>Ready to Take Your Business to the Next Level?</h2>
            <p>Join thousands of successful entrepreneurs who are growing their networks and increasing their earnings with MLM Pro.</p>
            <div class="cta-buttons-2">
                <a href="#" class="btn btn-white">Upgrade Your Account</a>
                <a href="#" class="btn btn-transparent">Contact Support</a>
            </div>
        </div>
    </section>
    
    
    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>MLM Pro</h3>
                    <p>Empowering network marketers with cutting-edge tools to build and manage successful businesses.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> My Account</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Network Overview</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Earnings Report</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Training Resources</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Support Center</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Resources</h3>
                    <ul>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Getting Started Guide</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Marketing Materials</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Training Webinars</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Success Stories</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> FAQs</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Company</h3>
                    <ul>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> About Us</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Compensation Plan</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Privacy Policy</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Terms of Service</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Contact Us</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                &copy; 2023 MLM Pro. All rights reserved.
            </div>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <script>
        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
        
        // Initialize charts
        document.addEventListener('DOMContentLoaded', function() {
            // Earnings Chart
            const earningsCtx = document.getElementById('earningsChart').getContext('2d');
            const earningsChart = new Chart(earningsCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Monthly Earnings',
                        data: [1200, 1900, 2300, 2800, 3200, 3500, 3845],
                        backgroundColor: 'rgba(110, 69, 226, 0.1)',
                        borderColor: '#6e45e2',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#6e45e2',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            
            // Growth Chart
            const growthCtx = document.getElementById('growthChart').getContext('2d');
            const growthChart = new Chart(growthCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'New Team Members',
                        data: [12, 19, 15, 24, 28, 32, 38],
                        backgroundColor: [
                            'rgba(110, 69, 226, 0.7)',
                            'rgba(110, 69, 226, 0.7)',
                            'rgba(110, 69, 226, 0.7)',
                            'rgba(110, 69, 226, 0.7)',
                            'rgba(110, 69, 226, 0.7)',
                            'rgba(110, 69, 226, 0.7)',
                            'rgba(45, 212, 191, 0.7)'
                        ],
                        borderColor: [
                            '#6e45e2',
                            '#6e45e2',
                            '#6e45e2',
                            '#6e45e2',
                            '#6e45e2',
                            '#6e45e2',
                            '#2dd4bf'
                        ],
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            
            // Animate elements when they come into view
            const animateOnScroll = function() {
                const elements = document.querySelectorAll('.feature-card, .testimonial-card, .stat-card, .analytics-card');
                
                elements.forEach(element => {
                    const elementPosition = element.getBoundingClientRect().top;
                    const windowHeight = window.innerHeight;
                    
                    if (elementPosition < windowHeight - 100) {
                        element.style.opacity = '1';
                        element.style.transform = 'translateY(0)';
                    }
                });
            };
            
            // Set initial state for animation
            document.querySelectorAll('.feature-card, .testimonial-card, .stat-card, .analytics-card').forEach(element => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(30px)';
                element.style.transition = 'all 0.6s ease';
            });
            
            window.addEventListener('scroll', animateOnScroll);
            animateOnScroll(); // Run once on load
        });
    </script>
</body>
</html>