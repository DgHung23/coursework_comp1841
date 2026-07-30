<?php
session_start();
require_once '../includes/DatabaseConnection.php';
require_once '../includes/DataBaseFunctions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    $_SESSION['error'] = 'Access denied. You must be an administrator.';
    header('Location: ../index.php');
    exit;
}

$title = 'Admin Dashboard';
ob_start();
?>
<div class="hero">
    <h1>Admin Area</h1>
    <p>Manage the Student Q&A Forum users, modules, and content.</p>
</div>
<div style="display: flex; gap: 2rem; justify-content: center; margin-top: 2rem;">
    <a href="users.php" class="glass-card" style="text-decoration: none; text-align: center; width: 250px;">
        <h2>Users</h2>
        <p>Manage student accounts</p>
    </a>
    <a href="categories.php" class="glass-card" style="text-decoration: none; text-align: center; width: 250px;">
        <h2>Modules</h2>
        <p>Manage course modules</p>
    </a>
</div>
<?php
$output = ob_get_clean();
include '../templates/admin_layout.html.php';
