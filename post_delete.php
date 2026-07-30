<?php
session_start();
require_once 'includes/DatabaseConnection.php';
require_once 'includes/DataBaseFunctions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $post_id = $_POST['id'];
    $post = getPost($pdo, $post_id);
    
    if ($post) {
        if ($post['author_id'] == $_SESSION['user_id'] || $_SESSION['role'] === 'ADMIN') {
            deletePost($pdo, $post_id);
            $_SESSION['success'] = 'Question deleted successfully.';
        } else {
            $_SESSION['error'] = 'You do not have permission to delete this question.';
        }
    }
}

header('Location: posts.php');
exit;
