<?php
require_once 'includes/DatabaseConnection.php';
require_once 'includes/DataBaseFunctions.php';

$username = 'admin';
$email = 'admin@example.com';
$password = 'admin123';
$role = 'ADMIN';

try {
    $stmt = $pdo->prepare('SELECT id FROM accounts WHERE username = :username');
    $stmt->execute([':username' => $username]);
    $account = $stmt->fetch();
    
    if ($account) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $update = $pdo->prepare('UPDATE accounts SET hashed_password = :hash, role = :role, email = :email, display_name = :display_name WHERE id = :id');
        $update->execute([':hash' => $hash, ':role' => $role, ':email' => $email, ':display_name' => 'Administrator', ':id' => $account['id']]);
        echo "Admin account updated! Username: admin, Password: admin123\n";
    } else {
        registerAccount($pdo, $username, $email, $password, $role, 'Administrator');
        echo "Admin account created successfully! Username: admin, Password: admin123\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
