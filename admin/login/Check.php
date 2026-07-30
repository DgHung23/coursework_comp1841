<?php
session_start();
if (!isset($_SESSION["Authorised"]) || $_SESSION["Authorised"] != "Y") {
    // Detect context: redirect path differs depending on whether the
    // calling script is in admin/ or admin/login/
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    if (basename($scriptDir) === 'login') {
        header("Location: Login.html");
    } else {
        header("Location: login/Login.html");
    }
    exit();
}
?>