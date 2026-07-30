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
        deleteAccount($pdo, $_POST['delete_id']);
        $_SESSION['success'] = 'User deleted successfully.';
    } elseif (isset($_POST['update_role_id'])) {
        updateAccountRole($pdo, $_POST['update_role_id'], $_POST['role']);
        $_SESSION['success'] = 'User role updated successfully.';
    }
    header('Location: users.php');
    exit;
}

$accounts = allAccounts($pdo);
$title = 'Manage Users - Admin';

ob_start();
?>
<div class="glass-card">
    <h2>Manage Users</h2>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Display Name</th>
                <th>Bio</th>
                <th>Role</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($accounts as $acc): ?>
            <tr>
                <td><?=htmlspecialchars($acc['username'])?></td>
                <td><?=htmlspecialchars($acc['email'])?></td>
                <td><?=htmlspecialchars($acc['display_name'] ?? '')?></td>
                <td><?=htmlspecialchars($acc['bio'] ?? '')?></td>
                <td>
                    <form action="users.php" method="POST" style="display: flex; gap: 0.5rem; align-items: center;">
                        <input type="hidden" name="update_role_id" value="<?=$acc['id']?>">
                        <select name="role" class="form-control" style="padding: 0.3rem; margin-bottom: 0;">
                            <option value="USER" <?=$acc['role'] === 'USER' ? 'selected' : ''?>>User</option>
                            <option value="ADMIN" <?=$acc['role'] === 'ADMIN' ? 'selected' : ''?>>Admin</option>
                        </select>
                        <button type="submit" class="btn-primary" style="padding: 0.3rem 0.5rem; font-size: 0.8rem;">Save</button>
                    </form>
                </td>
                <td><?=date('M j, Y', strtotime($acc['created_at']))?></td>
                <td>
                    <?php if ($acc['id'] !== $_SESSION['user_id']): ?>
                    <form action="users.php" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                        <input type="hidden" name="delete_id" value="<?=$acc['id']?>">
                        <button type="submit" class="btn-danger" style="padding: 0.3rem 0.5rem; font-size: 0.8rem;">Delete</button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
$output = ob_get_clean();
include '../templates/admin_layout.html.php';
