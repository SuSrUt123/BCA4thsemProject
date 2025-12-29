<?php
session_start();
include("../connection/connect.php");

// Set content type to JSON
header('Content-Type: application/json');

// Check if admin is logged in
if(empty($_SESSION["adm_id"]) && (empty($_SESSION["user_id"]) || empty($_SESSION["role"]) || $_SESSION["role"] !== 'admin')) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Validate user ID
if(!isset($_GET['user_del'])) {
    echo json_encode(['success' => false, 'message' => 'Missing user ID']);
    exit();
}

$user_id = filter_var($_GET['user_del'], FILTER_VALIDATE_INT);

if(!$user_id || $user_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
    exit();
}

// Check if trying to delete the logged-in admin
if(isset($_SESSION["adm_id"]) && $user_id == $_SESSION["adm_id"]) {
    echo json_encode(['success' => false, 'message' => 'You cannot delete your own account']);
    exit();
}

try {
    // Start transaction
    mysqli_begin_transaction($db);
    
    // Delete user's orders first (foreign key constraint)
    $stmt1 = $db->prepare("DELETE FROM users_orders WHERE u_id = ?");
    $stmt1->bind_param('i', $user_id);
    $stmt1->execute();
    
    // Delete user
    $stmt2 = $db->prepare("DELETE FROM users WHERE u_id = ?");
    $stmt2->bind_param('i', $user_id);
    
    if($stmt2->execute() && $stmt2->affected_rows > 0) {
        // Commit transaction
        mysqli_commit($db);
        echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
    } else {
        // Rollback transaction
        mysqli_rollback($db);
        echo json_encode(['success' => false, 'message' => 'User not found or already deleted']);
    }
    
} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($db);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

exit();
?>
