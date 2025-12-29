<?php
ob_start(); // Start output buffering
session_start();
error_reporting(0); // Suppress errors
include("../connection/connect.php");

// Check if admin is logged in - check both session variables for compatibility
if(empty($_SESSION["adm_id"]) && (empty($_SESSION["user_id"]) || empty($_SESSION["role"]) || $_SESSION["role"] !== 'admin')) {
    ob_end_clean();
    header('location:index.php');
    exit();
}

// Validate order ID
if(!isset($_GET['order_del'])) {
    ob_end_clean();
    header('location:all_orders.php?error=missing_id');
    exit();
}

$order_id = (int)$_GET['order_del'];

if($order_id <= 0) {
    ob_end_clean();
    header('location:all_orders.php?error=invalid_id');
    exit();
}

// Delete using simple query
$sql = "DELETE FROM users_orders WHERE o_id = " . $order_id;
$result = mysqli_query($db, $sql);

ob_end_clean(); // Clear output buffer
if($result) {
    header("location:all_orders.php?success=deleted");
} else {
    header("location:all_orders.php?error=delete_failed");
}
exit();
?>
