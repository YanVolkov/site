<?php 
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $dsn = "mysql:host=127.0.0.1;port=3306;dbname=guestbook;charset=utf8mb4";
    $username = 'root';
    $password = 'root';
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>