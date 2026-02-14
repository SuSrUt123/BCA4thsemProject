<?php
include("../connection/connect.php");
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = '';
$success = '';

// Redirect if already logged in
if(!empty($_SESSION["adm_id"])) {
    header('Location: dashboard.php');
    exit();
}

if(isset($_POST['submit'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if($username === '' || $password === '') {
        $message = "Please enter both username and password!";
    } else {
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND role = 'admin' LIMIT 1");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result && $result->num_rows === 1) {
            $row = $result->fetch_assoc();

            $password_valid = false;

            if(password_verify($password, $row['password'])) {
                $password_valid = true;
            } elseif(md5($password) === $row['password']) {
                $password_valid = true;
            }

            if($password_valid) {
                session_regenerate_id(true);
                $_SESSION["adm_id"] = $row['u_id'];
                $_SESSION["username"] = $row['username'];
                $_SESSION["role"] = $row['role'];

                $success = "Login successful! Redirecting...";
                header("Location: dashboard.php");
                exit();
            } else {
                $message = "Invalid Username or Password!";
            }
        } else {
            $message = "Invalid Username or Password!";
        }
    }
}
?>
