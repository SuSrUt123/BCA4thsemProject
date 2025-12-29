<?php
include("connection/connect.php");

echo "<h2>🔍 Admin Login Test</h2>";

$username = 'admin';
$password = 'admin123';

echo "<h3>Testing Login for: $username</h3>";

// Check if user exists
$stmt = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $row = $result->fetch_assoc();
    
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
    echo "<h4>✅ User Found in Database:</h4>";
    echo "<strong>ID:</strong> " . $row['u_id'] . "<br>";
    echo "<strong>Username:</strong> " . $row['username'] . "<br>";
    echo "<strong>Email:</strong> " . $row['email'] . "<br>";
    echo "<strong>Role:</strong> " . $row['role'] . "<br>";
    echo "<strong>Status:</strong> " . $row['status'] . "<br>";
    echo "<strong>Password Hash:</strong> " . $row['password'] . "<br>";
    echo "</div>";
    
    // Test password verification
    echo "<h4>🔐 Password Verification Test:</h4>";
    
    $password_valid = false;
    $method_used = '';
    
    // Test bcrypt first
    if (password_verify($password, $row['password'])) {
        $password_valid = true;
        $method_used = 'bcrypt (password_verify)';
    } 
    // Test MD5
    elseif (md5($password) === $row['password']) {
        $password_valid = true;
        $method_used = 'MD5';
    }
    
    if ($password_valid) {
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 8px; border-left: 4px solid #28a745;'>";
        echo "<h4>✅ Password Verification: SUCCESS</h4>";
        echo "<strong>Method:</strong> $method_used<br>";
        echo "<strong>Expected MD5:</strong> " . md5($password) . "<br>";
        echo "<strong>Stored Hash:</strong> " . $row['password'] . "<br>";
        echo "</div>";
        
        // Test role check
        if (!empty($row['role']) && $row['role'] === 'admin') {
            echo "<div style='background: #d4edda; padding: 15px; border-radius: 8px; border-left: 4px solid #28a745;'>";
            echo "<h4>✅ Role Check: SUCCESS</h4>";
            echo "<p>User has admin role - should redirect to admin/dashboard.php</p>";
            echo "</div>";
        } else {
            echo "<div style='background: #f8d7da; padding: 15px; border-radius: 8px; border-left: 4px solid #dc3545;'>";
            echo "<h4>❌ Role Check: FAILED</h4>";
            echo "<p>User role is: '" . $row['role'] . "' (should be 'admin')</p>";
            echo "</div>";
        }
        
    } else {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 8px; border-left: 4px solid #dc3545;'>";
        echo "<h4>❌ Password Verification: FAILED</h4>";
        echo "<strong>Expected MD5:</strong> " . md5($password) . "<br>";
        echo "<strong>Stored Hash:</strong> " . $row['password'] . "<br>";
        echo "<strong>Match:</strong> " . (md5($password) === $row['password'] ? 'YES' : 'NO') . "<br>";
        echo "</div>";
    }
    
} else {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 8px; border-left: 4px solid #dc3545;'>";
    echo "<h4>❌ User Not Found</h4>";
    echo "<p>No user found with username: $username</p>";
    echo "</div>";
}

echo "<hr><div style='background: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107;'>";
echo "<h4>📝 Next Steps:</h4>";
echo "<ol>";
echo "<li>If all tests above show ✅ SUCCESS, try logging in again at <a href='login.php'><strong>login.php</strong></a></li>";
echo "<li>Use exactly: <strong>admin</strong> / <strong>admin123</strong></li>";
echo "<li>Make sure you're using the main login page, not any admin-specific login</li>";
echo "</ol>";
echo "</div>";
?>