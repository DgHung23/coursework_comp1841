<?php
session_start();
$title = 'Home - Student Q&A Forum';
ob_start();
include 'templates/home.html.php';
$output = ob_get_clean();
include 'templates/layout.html.php';