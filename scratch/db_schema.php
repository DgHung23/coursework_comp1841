<?php
$pdo = new PDO('mysql:host=localhost;dbname=comp1841_coursework;charset=utf8mb4', 'root', '');
$query = $pdo->query('SHOW TABLES');
$tables = $query->fetchAll(PDO::FETCH_COLUMN);
if (empty($tables)) {
    echo "No tables found in comp1841_coursework.\n";
} else {
    foreach ($tables as $table) {
        echo "Table: $table\n";
        $q = $pdo->query("SHOW CREATE TABLE `$table`");
        $create = $q->fetch(PDO::FETCH_ASSOC);
        echo $create['Create Table'] . "\n\n";
    }
}
