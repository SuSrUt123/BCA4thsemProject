<?php
session_start();
include("../connection/connect.php");

// Disable caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Check if admin is logged in - match the dashboard authentication
if(empty($_SESSION["user_id"]) || empty($_SESSION["role"]) || $_SESSION["role"] !== 'admin') {
    header('location:index.php');
    exit();
}

// Validate user ID
if(!isset($_GET['user_del'])) {
    header('location:all_users.php?error=missing_id');
    exit();
}

$user_id = filter_var($_GET['user_del'], FILTER_VALIDATE_INT);

if(!$user_id || $user_id <= 0) {
    header('location:all_users.php?error=invalid_id');
    exit();
}

// Check if trying to delete the logged-in admin
if($user_id == $_SESSION["user_id"]) {
    header('location:all_users.php?error=cannot_delete_self');
    exit();
}

// Disable autocommit to use transactions
mysqli_autocommit($db, false);

try {
    // First, delete user's orders (foreign key constraint)
    $stmt_orders = $db->prepare("DELETE FROM users_orders WHERE u_id = ?");
    if(!$stmt_orders) {
        throw new Exception("Failed to prepare orders delete statement");
    }
    $stmt_orders->bind_param('i', $user_id);
    $stmt_orders->execute();
    $stmt_orders->close();
    
    // Then, permanently delete user from database
    $stmt_user = $db->prepare("DELETE FROM users WHERE u_id = ?");
    if(!$stmt_user) {
        throw new Exception("Failed to prepare user delete statement");
    }
    $stmt_user->bind_param('i', $user_id);
    
    if($stmt_user->execute()) {
        $affected = $stmt_user->affected_rows;
        $stmt_user->close();
        
        if($affected > 0) {
            // Commit the transaction
            mysqli_commit($db);
            
            // Re-enable autocommit
            mysqli_autocommit($db, true);
            
            // Redirect with success message and timestamp to prevent caching
            header("location:all_users.php?success=deleted&time=" . time());
            exit();
        } else {
            // No rows affected - user doesn't exist
            mysqli_rollback($db);
            mysqli_autocommit($db, true);
            
            header("location:all_users.php?error=user_not_found&time=" . time());
            exit();
        }
    } else {
        throw new Exception("Failed to execute user delete");
    }
} catch (Exception $e) {
    // Rollback on any error
    mysqli_rollback($db);
    mysqli_autocommit($db, true);
    
    // Log error for debugging
    error_log("User deletion error: " . $e->getMessage());
    
    header("location:all_users.php?error=delete_failed&msg=" . urlencode($e->getMessage()));
    exit();
}
?>
