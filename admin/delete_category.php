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

// Validate category ID
if(!isset($_GET['cat_del'])) {
    header('location:add_category.php?error=missing_id');
    exit();
}

$cat_id = (int)$_GET['cat_del'];

if($cat_id <= 0) {
    header('location:add_category.php?error=invalid_id');
    exit();
}

// Delete using simple query
$sql = "DELETE FROM res_category WHERE c_id = " . $cat_id;
$result = mysqli_query($db, $sql);

ob_end_clean(); // Clear output buffer
if($result) {
    header("location:add_category.php?success=deleted");
} else {
    header("location:add_category.php?error=delete_failed");
}
exit();
