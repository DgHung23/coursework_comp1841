<?php
$ActualPassword = "5ebe2294ecd0e0f08eab7690d2a6ee69";

if (hash("md5", $_POST["password"]) == $ActualPassword) {
    session_start();
    $_SESSION["Authorised"] = "Y";
    header("Location: ../jokes.php");
} else {
    header("Location: Wrongpassword.php");
}
?>