<?php
session_start();

$title = 'Contact - Student Q&A Forum';

ob_start();
include 'templates/contact.html.php';
$output = ob_get_clean();
include 'templates/layout.html.php';
