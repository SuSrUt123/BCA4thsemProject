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

// Validate menu ID
if(!isset($_GET['menu_del'])) {
    echo json_encode(['success' => false, 'message' => 'Missing menu ID']);
    exit();
}

$menu_id = filter_var($_GET['menu_del'], FILTER_VALIDATE_INT);

if(!$menu_id || $menu_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid menu ID']);
    exit();
}

try {
    // Delete menu item
    $stmt = $db->prepare("DELETE FROM dishes WHERE d_id = ?");
    $stmt->bind_param('i', $menu_id);
    
    if($stmt->execute() && $stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Menu item deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Menu item not found or already deleted']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

exit();
?>