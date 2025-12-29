<?php
session_start();
include("connection/connect.php");

$message = '';
$debug_info = [];

if (isset($_POST['submit'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $debug_info[] = "Form submitted with username: '$username'";
    $debug_info[] = "Password length: " . strlen($password);

    if ($username === '' || $password === '') {
        $message = "Please enter both username and password!";
        $debug_info[] = "Empty username or password detected";
    } else {
        $debug_info[] = "Preparing database query...";
        
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $debug_info[] = "Query executed. Rows found: " . $result->num_rows;

        if ($result && $result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $debug_info[] = "User found: ID=" . $row['u_id'] . ", Role=" . $row['role'];
            
            $password_valid = false;

            if (password_verify($password, $row['password'])) {
                $password_valid = true;
                $debug_info[] = "Password verified using bcrypt";
            } elseif (md5($password) === $row['password']) {
                $password_valid = true;
                $debug_info[] = "Password verified using MD5";
            } else {
                $debug_info[] = "Password verification failed. Expected: " . md5($password) . ", Got: " . $row['password'];
            }

            if ($password_valid) {
                $debug_info[] = "Password is valid, setting up session...";
                
                session_regenerate_id(true);
                $_SESSION["user_id"] = $row['u_id'];
                $_SESSION["role"] = $row['role'] ?? 'user';
                
                $debug_info[] = "Session set: user_id=" . $_SESSION["user_id"] . ", role=" . $_SESSION["role"];

                // Redirect based on role
                if (!empty($row['role']) && $row['role'] === 'admin') {
                    $debug_info[] = "Admin role detected, should redirect to admin/dashboard.php";
                    $message = "SUCCESS! Admin login detected. You should be redirected to admin panel.";
                    // Don't redirect in debug mode, show the result
                } else {
                    $debug_info[] = "Regular user role, should redirect to index.php";
                    $message = "SUCCESS! Regular user login.";
                }
            } else {
                $message = "Invalid Username or Password!";
                $debug_info[] = "Password validation failed";
            }
        } else {
            $message = "Invalid Username or Password!";
            $debug_info[] = "No user found with username: $username";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Debug Login</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .debug { background: #f0f0f0; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .success { background: #d4edda; border-left: 4px solid #28a745; }
        .error { background: #f8d7da; border-left: 4px solid #dc3545; }
        .form { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 400px; }
        input { width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px; }
        button { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>🔍 Debug Login Test</h1>
    
    <?php if (!empty($message)): ?>
    <div class="debug <?php echo (strpos($message, 'SUCCESS') !== false) ? 'success' : 'error'; ?>">
        <h3><?php echo (strpos($message, 'SUCCESS') !== false) ? '✅' : '❌'; ?> Result:</h3>
        <p><strong><?php echo $message; ?></strong></p>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($debug_info)): ?>
    <div class="debug">
        <h3>🔧 Debug Information:</h3>
        <ol>
            <?php foreach ($debug_info as $info): ?>
            <li><?php echo $info; ?></li>
            <?php endforeach; ?>
        </ol>
    </div>
    <?php endif; ?>
    
    <div class="form">
        <h2>Test Admin Login</h2>
        <form method="post">
            <label>Username:</label>
            <input type="text" name="username" value="admin" required>
            
            <label>Password:</label>
            <input type="password" name="password" value="admin123" required>
            
            <button type="submit" name="submit">Test Login</button>
        </form>
        
        <hr>
        <p><strong>Expected Credentials:</strong></p>
        <p>Username: <code>admin</code></p>
        <p>Password: <code>admin123</code></p>
        
        <hr>
        <p><a href="login.php">← Back to Main Login</a></p>
    </div>
</body>
</html>