<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/vendor/autoload.php';

session_start();

// Check if user is already logged in
if (isLoggedIn()) {
    header('Location: user-information/user-dashboard.php');
    exit;
}

$error = '';
$success = '';

// Check for success message from registration
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if it's a Google login
    if (isset($_POST['credential'])) {
        $credential = $_POST['credential'];
        try {
            $parts = explode('.', $credential);
            if (count($parts) !== 3) {
                throw new Exception('Invalid JWT: Incorrect number of parts');
            }
            
            $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1])), true);

            if ($payload === null && json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JWT Payload is not valid JSON: ' . json_last_error_msg());
            }

            $email = $payload['email'];
            
            // Find user by email
            $user = getUserByEmail($email);
            
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['account_id'] = $user['account_id'];
                $_SESSION['username'] = $user['username'];
                
                header('Location: user-information/user-dashboard.php');
                exit;
            } else {
                $error = 'No account found with this email. Please sign up first.';
            }
        } catch (Exception $e) {
            $error = 'Google Sign-In failed: ' . $e->getMessage();
        }
    } else {
        // Regular form login
        $accountId = trim($_POST['account_id'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Validate inputs
        if (empty($accountId) || empty($password)) {
            $error = 'Both fields are required';
        } else {
            // Attempt login
            $user = loginUser($accountId, $password);
            
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['account_id'] = $user['account_id'];
                $_SESSION['username'] = $user['username'];
                
                header('Location: user-information/user-dashboard.php');
                exit;
            } else {
                $error = 'Invalid account ID or password';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://accounts.google.com/gsi/client"></script>
    <style>
        :root {
            --primary: #5a55ae;
            --danger: #dc3545;
            --success: #28a745;
            --background: #f3f4f8;
            --card-bg: #ffffff;
            --text-dark: #2e2e2e;
            --border: #ddd;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-image: url('images/background.jpg');
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            background: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            max-width: 420px;
            width: 100%;
        }

        h1 {
            margin-bottom: 20px;
            text-align: center;
            color: white;
        }

        .alert {
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .alert.error {
            background: var(--danger);
            color: #fff;
        }

        .alert.success {
            background: var(--success);
            color: #fff;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: white;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--border);
            border-radius: 8px;
            transition: border 0.3s;
            color: white;
        }

        input:focus {
            border-color: var(--primary);
            outline: none;
            background: rgba(255, 255, 255, 0.2);
        }

        .btn {
            background: rgb(26, 65, 61);
            color: white;
            padding: 12px;
            border: none;
            width: 100%;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn:hover {
            background: rgb(20, 50, 45);
            transform: scale(1.02);
        }

        p {
            text-align: center;
            margin-top: 15px;
            color: white;
        }

        a {
            color: #4fc3f7;
            text-decoration: none;
            font-weight: 500;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 500px) {
            .container {
                padding: 30px 20px;
            }
        }

        #buttonDiv {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login to Your Account</h1>

        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form action="login.php" method="post" autocomplete="off">
            <div class="form-group">
                <label for="account_id">Account ID</label>
                <input type="text" id="account_id" name="account_id" value="<?= htmlspecialchars($_POST['account_id'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn">Login</button>
        </form>

        <div id="buttonDiv"></div>

        <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
        <!-- <p>Forgot your password? <a href="forgot_password.php">Reset it here</a></p> -->
    </div>

    <script>
 

        function handleCredentialResponse(response) {
            // Create a hidden form and submit it
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'login.php';
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'credential';
            input.value = response.credential;
            
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>