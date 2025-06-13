<?php
require_once __DIR__ . '/includes/auth.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    if (empty($email) || empty($password)) {
        $error = 'Both email and password are required';
    } else {
        if (login($email, $password)) {
            $_SESSION['user_email'] = $email;
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid email or password';
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
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-image:url('images/background.jpg');
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      /* background: white; */
      padding: 40px 30px;
      border-radius: 15px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
      max-width: 400px;
      width: 100%;
    }

    h1 {
      margin-bottom: 20px;
      text-align: center;
      font-size: 28px;
      color:white;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
      font-size: 14px;
      color:white;
    }

    input {
      width: 100%;
      padding: 12px;
      border-radius: 10px;
      border: 1px solid #ccc;
      font-size: 14px;
      background-color: transparent;
      color: white;
    }

    .btn {
      width: 100%;
      padding: 12px;
      background-color:rgb(26, 65, 61);
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .btn:hover {
      background-color:rgb(26, 65, 61);
      font-size:larger;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .alert.error {
      background: #ffe0e0;
      color: #b10000;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 14px;
    }

    p {
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
    }

    a {
      color: #007bff;
      text-decoration: none;
      font-weight: bold;
    }

    a:hover {
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      .container {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Login</h1>

    <?php if ($error): ?>
      <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="login.php" method="post">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>

      <button type="submit" class="btn">Login</button>
    </form>

    <p style="color:white;">Don't have an account? <a href="signup.php">Sign Up</a></p>
  </div>
</body>
</html>
