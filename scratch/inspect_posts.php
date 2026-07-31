<?php
$pdo = new PDO('mysql:host=localhost;dbname=comp1841_coursework;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = 'SELECT p.id, p.title, p.image, p.created_at, a.username AS author_username
        FROM post p
        INNER JOIN accounts a ON p.author_id = a.id
        ORDER BY p.created_at DESC';
$posts = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

foreach ($posts as $post) {
    $cats = $pdo->prepare('SELECT c.name FROM category c INNER JOIN post_category pc ON c.id = pc.category_id WHERE pc.post_id = :id ORDER BY c.name');
    $cats->execute([':id' => $post['id']]);
    $names = array_column($cats->fetchAll(PDO::FETCH_ASSOC), 'name');
    echo $post['id'] . ' | ' . $post['title'] . ' | image=' . ($post['image'] ?: 'none') . ' | author=' . $post['author_username'] . ' | categories=' . implode(', ', $names) . PHP_EOL;
}
