<?php
    session_start();

    session_unset();

    session_destroy();

    header("Location: /UAS/login.php");
    exit;
?>
