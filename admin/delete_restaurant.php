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

// Validate restaurant ID
if(!isset($_GET['res_del'])) {
    echo json_encode(['success' => false, 'message' => 'Missing restaurant ID']);
    exit();
}

$res_id = filter_var($_GET['res_del'], FILTER_VALIDATE_INT);

if(!$res_id || $res_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid restaurant ID']);
    exit();
}

try {
    // Start transaction
    mysqli_begin_transaction($db);
    
    // Delete dishes associated with this restaurant first
    $stmt1 = $db->prepare("DELETE FROM dishes WHERE rs_id = ?");
    $stmt1->bind_param('i', $res_id);
    $stmt1->execute();
    
    // Delete restaurant
    $stmt2 = $db->prepare("DELETE FROM restaurant WHERE rs_id = ?");
    $stmt2->bind_param('i', $res_id);
    
    if($stmt2->execute() && $stmt2->affected_rows > 0) {
        // Commit transaction
        mysqli_commit($db);
        echo json_encode(['success' => true, 'message' => 'Restaurant deleted successfully']);
    } else {
        // Rollback transaction
        mysqli_rollback($db);
        echo json_encode(['success' => false, 'message' => 'Restaurant not found or already deleted']);
    }
    
} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($db);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

exit();
?>