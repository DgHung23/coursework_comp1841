<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // In a real application, you would send an email here using mail() or PHPMailer.
    // For this prototype, we'll just simulate success.
    $_SESSION['success'] = 'Your message has been sent to the administrator. We will get back to you soon.';
    header('Location: index.php');
    exit;
}

$title = 'Contact - Student Q&A Forum';

ob_start();
include 'templates/contact.html.php';
$output = ob_get_clean();
include 'templates/layout.html.php';
