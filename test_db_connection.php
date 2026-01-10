<?php
/**
 * Database Connection Test
 * This file tests the connection to the new 'quickbites' database
 */

echo "<h2>🔍 Testing Database Connection</h2>";

// Include the connection file
include("connection/connect.php");

if ($db) {
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
    echo "<h3>✅ Database Connection Successful!</h3>";
    echo "<strong>Database Name:</strong> quickbites<br>";
    echo "<strong>Server:</strong> localhost<br>";
    echo "<strong>Connection Status:</strong> Active<br>";
    
    // Test a simple query to verify tables exist
    $test_query = "SHOW TABLES";
    $result = mysqli_query($db, $test_query);
    
    if ($result) {
        $table_count = mysqli_num_rows($result);
        echo "<strong>Tables Found:</strong> $table_count<br>";
        
        echo "<h4>📋 Available Tables:</h4>";
        echo "<ul>";
        while ($row = mysqli_fetch_array($result)) {
            echo "<li>" . $row[0] . "</li>";
        }
        echo "</ul>";
    }
    
    echo "</div>";
} else {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
    echo "<h3>❌ Database Connection Failed!</h3>";
    echo "<strong>Error:</strong> " . mysqli_connect_error() . "<br>";
    echo "<strong>Note:</strong> Make sure you have created the 'quickbites' database and imported the SQL file.";
    echo "</div>";
}

echo "<hr>";
echo "<h3>📝 Next Steps:</h3>";
echo "<ol>";
echo "<li>Create a database named '<strong>quickbites</strong>' in phpMyAdmin</li>";
echo "<li>Import the SQL file: <code>DATABASE FILE/quickbites.sql</code></li>";
echo "<li>Refresh this page to test the connection</li>";
echo "<li>Delete this test file when done: <code>test_db_connection.php</code></li>";
echo "</ol>";
?>