<?php
require_once __DIR__ . '/includes/auth.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $referenceCode = isset($_POST['reference_code']) ? trim($_POST['reference_code']) : null;

    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'All fields are required';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        if ($referenceCode) {
            $sponsor = getUserByReferenceCode($referenceCode);
            if (!$sponsor) {
                $error = 'Invalid reference code';
            }
        }

        if (!$error) {
            $userId = registerUser($username, $email, $password, $referenceCode);
            if ($userId) {
                $success = 'Registration successful. You can now login.';
                header('Location: login.php');
                exit;
            } else {
                $error = 'Registration failed. Please try again.';
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
    <title>Sign Up</title>
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
            background-image:url('images/background.jpg');
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            background: transparent;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            max-width: 420px;
            width: 100%;
        }

        h1 {
            margin-bottom: 20px;
            
            text-align: center;
            color:white;
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
            color: var(--text-dark);
            color:white;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 8px;
            transition: border 0.3s;
            color: white;
        }

        input:focus {
            border-color: var(--primary);
            outline: none;
        }

        .btn {
            background: rgb(26, 65, 61);
            color: white;
            padding: 12px;
            border: none;
            width: 105%;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn:hover {
            background:rgb(26, 65, 61);
            font-size:larger;
        }

        p {
            text-align: center;
            margin-top: 15px;
        }

        a {
            color: var(--primary);
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 500px) {
            .container {
                padding: 30px 20px;
            }
        }
         .g_id_signin {
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create Account</h1>

        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form action="signup.php" method="post" autocomplete="off">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password (Min. 6 characters)</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <div class="form-group">
                <label for="reference_code">Referral Code (optional)</label>
                <input type="text" id="reference_code" name="reference_code" value="<?= htmlspecialchars($_POST['reference_code'] ?? '') ?>">
            </div>

            <button type="submit" class="btn">Register</button>
             <div id="g_id_onload"
         data-client_id="YOUR_GOOGLE_CLIENT_ID"
         data-login_uri="signup.php"
         data-auto_prompt="false">
  </div>
  <div class="g_id_signin"
         data-type="standard"
         data-size="large"
         data-theme="outline"
         data-text="sign_up_with"
         data-shape="rectangular"
         data-logo_alignment="left">
  </div>
        </form>

        <p style="color:white;">Already registered? <a href="login.php">Login here</a></p>
    </div>
     <script>
    window.onload = function () {
      google.accounts.id.initialize({
        client_id: 'YOUR_GOOGLE_CLIENT_ID',
        callback: handleCredentialResponse
      });
      google.accounts.id.renderButton(
        document.getElementById("buttonDiv"),
        { theme: "outline", size: "large" }  // customization attributes
      );
      google.accounts.id.prompt(); // also display the One Tap sign-in prompt
    }
    function handleCredentialResponse(response) {
      console.log("Encoded JWT ID token: " + response.credential);
      // Send credential to server
      fetch('signup.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'credential=' + encodeURIComponent(response.credential)
      })
      .then(response => {
            if (!response.ok) {
                throw new Error('HTTP error, status = ' + response.status);
            }
            return response.text();
        })
      .then(data => {
        console.log('Server response:', data);
        // Handle server response (e.g., display success/error message)
         if (data.includes('Registration successful')) {
                    window.location.href = 'login.php'; // Redirect to login page on success
                } else {
                    alert(data); // Show error message
                }
      })
      .catch((error) => {
        console.error('Error:', error);
        alert('Google Sign-In failed. Check console for details.');
        // Handle error (e.g., display error message)
      });
    }
  </script>
</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['credential'])) {
    $credential = $_POST['credential'];
    try {
        // Decode JWT and get user info
        $parts = explode('.', $credential);
        if (count($parts) !== 3) {
            throw new Exception('Invalid JWT: Incorrect number of parts');
        }
        $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1])), true);

         if ($payload === null && json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JWT Payload is not valid JSON: ' . json_last_error_msg());
            }

        $email = $payload['email'];
        $username = $payload['name'];

        // Register user
        if (!$error) {
            $userId = registerUser($username, $email, generateRandomPassword(), null);
            if ($userId) {
                $success = 'Registration successful. You can now login.';
                 echo $success;
                exit;
            } else {
                $error = 'Registration failed. Please try again.';
                 echo $error;
                
                exit;
            }
        }
    } catch (Exception $e) {
        $error = 'Google Sign-In failed: ' . $e->getMessage();
         echo $error;
    }
}
function generateRandomPassword($length = 10) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}
?>
