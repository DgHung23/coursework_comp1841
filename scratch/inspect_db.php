<?php
$pdo = new PDO('mysql:host=localhost;dbname=comp1841_coursework;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

foreach (['accounts', 'category', 'post', 'post_category'] as $table) {
    $count = $pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
    echo $table . ':' . $count . PHP_EOL;
}

$rows = $pdo->query('SELECT id, username, email, role, display_name FROM accounts ORDER BY id LIMIT 10')->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
