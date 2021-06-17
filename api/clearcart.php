<?php
    session_start();
    unset($_SESSION['cart']);
    http_response_code(200);
    header('Content-Type: application/json');
    echo '{"status":"success"}';
    exit();