<?php
$pdo = new PDO('mysql:host=localhost;dbname=comp1841_coursework;charset=utf8mb4', 'root', '');
// Ensure PDO throws exceptions on error
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);