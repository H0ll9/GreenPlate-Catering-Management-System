<?php
session_start();
require_once 'config/db.php';

// --- SECURITY CHECK ---
if (isset($_SESSION['admin_logged_in'])) {
    header("location: admin.php");
    exit;
}

 $error = '';

// --- HANDLE LOGIN LOGIC ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Auto-Setup: If table is empty, create default admin (For your convenience)
    $checkTable = $conn->query("SHOW TABLES LIKE 'admins'");
    if($checkTable->num_rows == 0) {
        $conn->query("CREATE TABLE admins (id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(50), password VARCHAR(255))");
    }
    
    // Check if admin user exists, if not create one (admin/admin123)
    $checkUser = $conn->query("SELECT * FROM admins WHERE username='admin'");
    if ($checkUser->num_rows == 0) {
        $hash = password_hash("admin123", PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES ('admin', ?)");
        $stmt->bind_param("s", $hash);
        $stmt->execute();
    }

    // Verify User
    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Verify Password Hash
        if (password_verify($password, $row['password'])) {
            // Password is correct, start session
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_username'] = $username;
            
            // Redirect to Admin Dashboard
            header("location: admin.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Invalid username.";
    }
    $stmt->close();
}
 $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f7f9fc; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 400px; text-align: center; }
        h2 { color: #2d3436; margin-bottom: 10px; }
        p { color: #636e72; font-size: 0.9rem; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; text-align: left; }
        label { display: block; margin-bottom: 8px; font-weight: 500; color: #636e72; font-size: 0.9rem; }
        input { width: 100%; padding: 12px; border: 1px solid #dfe6e9; border-radius: 6px; box-sizing: border-box; font-family: inherit; }
        input:focus { outline: none; border-color: #d63031; }
        button { width: 100%; padding: 12px; background: #d63031; color: white; border: none; border-radius: 6px; font-size: 16px; font-weight: 600; cursor: pointer; }
        button:hover { background: #b71540; }
        .error { color: #d63031; background: #ffdede; padding: 10px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; }
        .back-link { display: block; margin-top: 20px; color: #0984e3; text-decoration: none; font-size: 0.9rem; }
    </style>
</head>
<body>

<div class="login-card">
    <h2>🔐 Admin Login</h2>
    <p>Please sign in to manage requests.</p>

    <?php if($error != ""): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required placeholder="Enter username">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required placeholder="Enter password">
        </div>
        <button type="submit">Login</button>
    </form>

    <a href="index.php" class="back-link">← Back to Home</a>
</div>

</body>
</html>