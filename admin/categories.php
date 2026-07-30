<?php
session_start();
require_once '../includes/DatabaseConnection.php';
require_once '../includes/DataBaseFunctions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        deleteCategory($pdo, $_POST['delete_id']);
        $_SESSION['success'] = 'Module deleted successfully.';
    } elseif (isset($_POST['edit_id'])) {
        updateCategory($pdo, $_POST['edit_id'], $_POST['name'], $_POST['description']);
        $_SESSION['success'] = 'Module updated successfully.';
    } elseif (isset($_POST['add_module'])) {
        insertCategory($pdo, $_POST['name'], $_POST['description']);
        $_SESSION['success'] = 'Module added successfully.';
    }
    header('Location: categories.php');
    exit;
}

$categories = allCategories($pdo);
$title = 'Manage Modules - Admin';

ob_start();
?>
<div class="glass-card" style="margin-bottom: 2rem;">
    <h2>Add New Module</h2>
    <form action="categories.php" method="POST" style="display: flex; gap: 1rem; align-items: flex-end;">
        <input type="hidden" name="add_module" value="1">
        <div class="form-group" style="margin-bottom: 0; flex: 1;">
            <label for="name">Module Code / Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group" style="margin-bottom: 0; flex: 2;">
            <label for="description">Description</label>
            <input type="text" name="description" class="form-control">
        </div>
        <button type="submit" class="btn-primary" style="height: fit-content; padding: 0.8rem 1.5rem;">Add</button>
    </form>
</div>

<div class="glass-card">
    <h2>Existing Modules</h2>
    <table>
        <thead>
            <tr>
                <th>Module Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $cat): ?>
            <tr>
                <td><?=htmlspecialchars($cat['name'])?></td>
                <td><?=htmlspecialchars($cat['description'])?></td>
                <td>
                    <!-- Simple implementation: only delete, edit can be similar but requires more UI. Let's add basic delete for now to meet requirements. -->
                    <form action="categories.php" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this module?');">
                        <input type="hidden" name="delete_id" value="<?=$cat['id']?>">
                        <button type="submit" class="btn-danger" style="padding: 0.3rem 0.5rem; font-size: 0.8rem;">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
$output = ob_get_clean();
include '../templates/admin_layout.html.php';
