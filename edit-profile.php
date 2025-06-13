<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$profilePic = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    // Profile Image Upload Handling
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $filename = time() . '_' . basename($_FILES['profile_pic']['name']);
        $targetFile = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFile)) {
            $profilePic = $targetFile;
        }
    }

    // Update query
    if ($profilePic) {
        $query = "UPDATE users SET username = ?, email = ?, profile_pic = ? WHERE id = ?";
        $params = [$username, $email, $profilePic, $userId];
    } else {
        $query = "UPDATE users SET username = ?, email = ? WHERE id = ?";
        $params = [$username, $email, $userId];
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    header('Location: dashboard.php');
    exit;
}

// Fetch current user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<div style="max-width: 600px; margin: auto; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    <h2 style="color:blue;">Edit Profile</h2>
<form action="edit-profile.php" method="POST" enctype="multipart/form-data">
    <label>Username</label>
    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

    <label>Profile Picture</label>
    <!-- <?php if (!empty($user['profile_pic'])): ?>
        <div><img src="<?= htmlspecialchars($user['profile_pic']) ?>" width="100" style="border-radius: 50%;" /></div>
    <?php endif; ?> -->
    <input type="file" name="profile_pic" accept="image/*">

    <button type="submit">Update Profile</button>
    <a href="dashboard.php"><button type="button">Cancel</button></a>
</form>
</div>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f2f2f2;
        margin: 0;
        padding: 30px;
    }

    .form-container {
        background: #ffffff;
        padding: 30px;
        max-width: 500px;
        margin: auto;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border:2px solid red;
    }

    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 25px;
    }

    label {
        display: block;
        margin: 10px 0 5px;
        color: #555;
        font-weight: bold;
        
    }

    input[type="text"],
    input[type="email"],
    input[type="file"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    img {
        display: block;
        margin-bottom: 15px;
        max-width: 100px;
        height: auto;
        /* border-radius: 50%; */
        border: 1px solid #ddd;
    }

    .button-group {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    button {
        background: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 15px;
    }

    button:hover {
        background: #0056b3;
    }

    button[type="button"] {
        background: #6c757d;
    }

    button[type="button"]:hover {
        background: #5a6268;
    }

    @media (max-width: 600px) {
        .form-container {
            padding: 20px;
        }

        button {
            width: 48%;
        }

        .button-group {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>
