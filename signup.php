<?php
session_start();
require_once 'includes/DatabaseConnection.php';
require_once 'includes/DataBaseFunctions.php';

$action = 'signup';
$title = 'Sign Up - Student Q&A Forum';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $display_name = trim($_POST['display_name'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    
    try {
        registerAccount($pdo, $username, $email, $password, 'USER', $display_name, $bio);
        
        $_SESSION['success'] = 'Account created successfully! Please log in.';
        header('Location: login.php');
        exit;
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            $_SESSION['error'] = 'Username or email already exists.';
        } else {
            $_SESSION['error'] = 'An error occurred during registration. Please try again.';
        }
    }
}

ob_start();
include 'templates/auth_form.html.php';
$output = ob_get_clean();
include 'templates/layout.html.php';
