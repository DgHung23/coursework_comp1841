<?php
session_start();
require_once 'includes/DatabaseConnection.php';
require_once 'includes/DataBaseFunctions.php';

try {
    $selectedCategoryId = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);
    if ($selectedCategoryId === false || $selectedCategoryId <= 0) {
        $selectedCategoryId = null;
    }

    $categories = allCategories($pdo);
    $selectedCategory = null;
    foreach ($categories as $category) {
        if ((int)$category['id'] === (int)$selectedCategoryId) {
            $selectedCategory = $category;
            break;
        }
    }
    if ($selectedCategoryId !== null && $selectedCategory === null) {
        $selectedCategoryId = null;
    }

    $posts = allPosts($pdo, $selectedCategoryId);
    $totalPosts = totalPosts($pdo, $selectedCategoryId);
    $title = 'Questions - Student Q&A Forum';

    ob_start();
    include 'templates/posts.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . $e->getMessage();
}

include 'templates/layout.html.php';
