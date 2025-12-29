<?php
include("connection/connect.php");

echo "<h2>Admin User Setup</h2>";

// Check for admin users
$sql = "SELECT * FROM users WHERE role = 'admin'";
$result = mysqli_query($db, $sql);

if(mysqli_num_rows($result) > 0) {
    echo "<h3 style='color: green;'>✅ Admin User Already Exists!</h3>";
    while($row = mysqli_fetch_array($result)) {
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
        echo "<strong>Username:</strong> " . $row['username'] . "<br>";
        echo "<strong>Email:</strong> " . $row['email'] . "<br>";
        echo "<strong>Role:</strong> " . $row['role'] . "<br>";
        echo "</div>";
    }
    echo "<h3>🔑 Login Credentials:</h3>";
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107;'>";
    echo "<strong>Username:</strong> admin<br>";
    echo "<strong>Password:</strong> admin123<br>";
    echo "<strong>Login URL:</strong> <a href='login.php'>login.php</a> (main site login)<br>";
    echo "</div>";
} else {
    echo "<h3 style='color: orange;'>⚠️ No Admin User Found!</h3>";
    echo "<p>Creating admin user now...</p>";
    
    // Create admin user
    $admin_password = md5('admin123');
    $insert_sql = "INSERT INTO users (username, f_name, l_name, email, phone, password, address, status, role, date) 
                   VALUES ('admin', 'Admin', 'User', 'admin@mail.com', '0000000000', '$admin_password', 'Admin Address', 1, 'admin', NOW())";
    
    if(mysqli_query($db, $insert_sql)) {
        echo "<h3 style='color: green;'>✅ Admin User Created Successfully!</h3>";
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 8px; border-left: 4px solid #28a745;'>";
        echo "<strong>Username:</strong> admin<br>";
        echo "<strong>Password:</strong> admin123<br>";
        echo "<strong>Login URL:</strong> <a href='login.php'>login.php</a> (main site login)<br>";
        echo "</div>";
        echo "<p style='color: green;'>✅ You can now login using the main site login page!</p>";
    } else {
        echo "<p style='color: red;'>❌ Error creating admin user: " . mysqli_error($db) . "</p>";
        
        // Try alternative method
        echo "<h4>Trying alternative method...</h4>";
        $alt_sql = "INSERT INTO users SET 
                    username = 'admin',
                    f_name = 'Admin',
                    l_name = 'User', 
                    email = 'admin@mail.com',
                    phone = '0000000000',
                    password = '$admin_password',
                    address = 'Admin Address',
                    status = 1,
                    role = 'admin'";
        
        if(mysqli_query($db, $alt_sql)) {
            echo "<p style='color: green;'>✅ Admin user created with alternative method!</p>";
        } else {
            echo "<p style='color: red;'>❌ Alternative method also failed: " . mysqli_error($db) . "</p>";
        }
    }
}

echo "<hr><h3>📊 All Users in Database:</h3>";
$all_users_sql = "SELECT u_id, username, email, role FROM users ORDER BY role DESC, u_id ASC";
$all_result = mysqli_query($db, $all_users_sql);

if(mysqli_num_rows($all_result) > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
    echo "<tr style='background: #f8f9fa;'><th style='padding: 10px;'>ID</th><th style='padding: 10px;'>Username</th><th style='padding: 10px;'>Email</th><th style='padding: 10px;'>Role</th></tr>";
    while($row = mysqli_fetch_array($all_result)) {
        $bg_color = ($row['role'] == 'admin') ? '#d4edda' : '#ffffff';
        echo "<tr style='background: $bg_color;'>";
        echo "<td style='padding: 8px; text-align: center;'>" . $row['u_id'] . "</td>";
        echo "<td style='padding: 8px;'><strong>" . $row['username'] . "</strong></td>";
        echo "<td style='padding: 8px;'>" . $row['email'] . "</td>";
        echo "<td style='padding: 8px; text-align: center;'><span style='background: " . ($row['role'] == 'admin' ? '#28a745' : '#6c757d') . "; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;'>" . strtoupper($row['role']) . "</span></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>❌ No users found in database!</p>";
}

echo "<hr><div style='background: #e7f3ff; padding: 15px; border-radius: 8px; border-left: 4px solid #2196F3;'>";
echo "<h4>📝 Instructions:</h4>";
echo "<ol>";
echo "<li>Go to the main site login page: <a href='login.php'><strong>login.php</strong></a></li>";
echo "<li>Enter Username: <strong>admin</strong></li>";
echo "<li>Enter Password: <strong>admin123</strong></li>";
echo "<li>Click Login - you will be automatically redirected to the admin dashboard</li>";
echo "</ol>";
echo "</div>";
?>