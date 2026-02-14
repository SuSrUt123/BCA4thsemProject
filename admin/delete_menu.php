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

// Validate menu ID
if(!isset($_GET['menu_del'])) {
    header('location:all_menu.php?error=missing_id');
    exit();
}

$menu_id = (int)$_GET['menu_del'];

if($menu_id <= 0) {
    header('location:all_menu.php?error=invalid_id');
    exit();
}

// Delete using simple query
$sql = "DELETE FROM dishes WHERE d_id = " . $menu_id;
$result = mysqli_query($db, $sql);

ob_end_clean(); // Clear output buffer
if($result) {
    header("location:all_menu.php?success=deleted");
} else {
    header("location:all_menu.php?error=delete_failed");
}
exit();
