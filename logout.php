<?php
    session_start();
    unset($_SESSION['user']);
    return header("Location: /index.php");
