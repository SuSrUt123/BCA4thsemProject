<?php

/**
 * Secure Database Connection File
 * This file provides a secure database connection with proper error handling
 */

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "onlinefoodphp";

// Create connection with error handling
try {
    $db = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($db->connect_error) {
        // Log error instead of displaying it
        error_log("Database connection failed: " . $db->connect_error);
        die("Sorry, we're experiencing technical difficulties. Please try again later.");
    }

    // Set charset to prevent SQL injection via charset
    $db->set_charset("utf8mb4");
} catch (Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("Sorry, we're experiencing technical difficulties. Please try again later.");
}

/**
 * Helper function to safely execute prepared statements
 * 
 * @param mysqli $db Database connection
 * @param string $query SQL query with placeholders
 * @param string $types Parameter types (i=integer, d=double, s=string, b=blob)
 * @param array $params Parameters to bind
 * @return mysqli_result|bool Query result or false on failure
 */
function safe_query($db, $query, $types = "", $params = [])
{
    $stmt = $db->prepare($query);

    if (!$stmt) {
        error_log("Prepare failed: " . $db->error);
        return false;
    }

    if (!empty($types) && !empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        return false;
    }

    return $stmt->get_result();
}

/**
 * Sanitize output to prevent XSS
 * 
 * @param string $data Data to sanitize
 * @return string Sanitized data
 */
function clean_output($data)
{
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email address
 * 
 * @param string $email Email to validate
 * @return bool True if valid, false otherwise
 */
function validate_email($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number (Nepal format)
 * 
 * @param string $phone Phone number to validate
 * @return bool True if valid, false otherwise
 */
function validate_phone($phone)
{
    // Remove spaces and dashes
    $phone = preg_replace('/[\s\-]/', '', $phone);
    // Check if it's 10 digits starting with 9
    return preg_match('/^9[0-9]{9}$/', $phone);
}
