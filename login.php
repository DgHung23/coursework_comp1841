<?php
session_start();
require_once 'includes/DatabaseConnection.php';
require_once 'includes/DataBaseFunctions.php';

$action = 'login';
$title = 'Login - Student Q&A Forum';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $account = getAccountByEmail($pdo, $email);
    if ($account && password_verify($password, $account['hashed_password'])) {
        $_SESSION['user_id'] = $account['id'];
        $_SESSION['email'] = $account['email'];
        $_SESSION['username'] = $account['username'];
        $_SESSION['role'] = $account['role'];
        $_SESSION['display_name'] = $account['display_name'] ?: $account['username'];
        $_SESSION['bio'] = $account['bio'] ?? '';
        
        $_SESSION['success'] = 'Welcome back, ' . htmlspecialchars($account['username']) . '!';
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['error'] = 'Invalid email or password.';
    }
}

ob_start();
include 'templates/auth_form.html.php';
$output = ob_get_clean();
include 'templates/layout.html.php';
