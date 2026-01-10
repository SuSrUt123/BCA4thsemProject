<?php
/**
 * Quick Bites Setup Verification
 * This file verifies that the project has been set up correctly
 */

echo "<h1>🍕 Quick Bites - Setup Verification</h1>";
echo "<hr>";

// Check if we're in the right directory
$currentDir = basename(getcwd());
echo "<h3>📁 Directory Check:</h3>";
if ($currentDir === 'quick-bites') {
    echo "<p style='color: green;'>✅ Correct directory: $currentDir</p>";
} else {
    echo "<p style='color: orange;'>⚠️ Current directory: $currentDir (Expected: quick-bites)</p>";
}

// Check if key files exist
echo "<h3>📄 File Structure Check:</h3>";
$keyFiles = [
    'index.php' => 'Homepage',
    'connection/connect.php' => 'Database Connection',
    'admin/index.php' => 'Admin Panel',
    'DATABASE FILE/quickbites.sql' => 'Database Schema',
    'css/dark-theme.css' => 'Dark Theme Styles',
    'README.md' => 'Documentation'
];

foreach ($keyFiles as $file => $description) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ $description: $file</p>";
    } else {
        echo "<p style='color: red;'>❌ Missing: $file ($description)</p>";
    }
}

// Check database connection
echo "<h3>🗄️ Database Connection:</h3>";
try {
    include("connection/connect.php");
    if ($db) {
        echo "<p style='color: green;'>✅ Database connection successful!</p>";
        echo "<p><strong>Database:</strong> quickbites</p>";
        
        // Test a simple query
        $result = mysqli_query($db, "SHOW TABLES");
        if ($result) {
            $tableCount = mysqli_num_rows($result);
            echo "<p><strong>Tables found:</strong> $tableCount</p>";
        }
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
    echo "<p><strong>Note:</strong> Make sure to create the 'quickbites' database and import the SQL file.</p>";
}

echo "<hr>";
echo "<h3>🚀 Quick Start:</h3>";
echo "<ol>";
echo "<li><strong>Homepage:</strong> <a href='index.php'>index.php</a></li>";
echo "<li><strong>Admin Panel:</strong> <a href='admin/'>admin/</a></li>";
echo "<li><strong>Database Test:</strong> <a href='test_db_connection.php'>test_db_connection.php</a></li>";
echo "</ol>";

echo "<hr>";
echo "<p><em>Delete this file (verify_setup.php) when setup is complete.</em></p>";
?>