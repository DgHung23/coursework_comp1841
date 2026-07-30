<?php
session_start();
require_once 'includes/DatabaseConnection.php';
require_once 'includes/DataBaseFunctions.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'You must be logged in to ask a question.';
    header('Location: login.php');
    exit;
}

$categories = allCategories($pdo);
$post = [];

if (isset($_GET['id'])) {
    $post = getPost($pdo, $_GET['id']);
    if (!$post) {
        $_SESSION['error'] = 'Question not found.';
        header('Location: posts.php');
        exit;
    }
    // Check permissions
    if ($post['author_id'] != $_SESSION['user_id'] && $_SESSION['role'] !== 'ADMIN') {
        $_SESSION['error'] = 'You do not have permission to edit this question.';
        header('Location: posts.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $category_ids = $_POST['category_ids'] ?? [];
    if (!is_array($category_ids)) {
        $category_ids = !empty($category_ids) ? [$category_ids] : [];
    }
    $post_id = $_POST['post_id'] ?? null;
    $author_id = $_SESSION['user_id'];
    
    // Handle image upload with secure unique filename generation & extension validation
    $image = null;
    $imageUploadError = false;
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $originalFileName = $_FILES['image']['name'];
        $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
        
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($fileExtension, $allowedExtensions)) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Generate a 100% unique filename using random bytes + timestamp + extension
            $uniqueName = bin2hex(random_bytes(16)) . '_' . time() . '.' . $fileExtension;
            $targetPath = $uploadDir . $uniqueName;
            
            if (move_uploaded_file($fileTmpPath, $targetPath)) {
                $image = $uniqueName;
            } else {
                $_SESSION['error'] = 'Failed to upload image file to server.';
                $imageUploadError = true;
            }
        } else {
            $_SESSION['error'] = 'Invalid file format. Only JPG, JPEG, PNG, GIF, and WEBP files are allowed.';
            $imageUploadError = true;
        }
    } else if (isset($post['image'])) {
        $image = $post['image']; // Keep existing image if no new one uploaded
    }

    if (!$imageUploadError) {
        try {
            if ($post_id) {
                updatePost($pdo, $post_id, $title, $content, $image, $category_ids);
                $_SESSION['success'] = 'Question updated successfully.';
            } else {
                insertPost($pdo, $author_id, $title, $content, $image, $category_ids);
                $_SESSION['success'] = 'Question posted successfully.';
            }
            header('Location: posts.php');
            exit;
        } catch (PDOException $e) {
            $_SESSION['error'] = 'An error occurred: ' . $e->getMessage();
        }
    }
}

$title = isset($_GET['id']) ? 'Edit Question - Student Q&A Forum' : 'Ask Question - Student Q&A Forum';

ob_start();
include 'templates/post_form.html.php';
$output = ob_get_clean();
include 'templates/layout.html.php';
