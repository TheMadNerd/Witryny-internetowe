<?php
$pdo=new PDO(
    'pgsql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASSWORD') ?: getenv('DB_PASS')
);
$stmt=$pdo->prepare('SELECT email,password_hash FROM users WHERE email=:e');
$stmt->execute([':e'=>'admin@example.com']);
var_dump($stmt->fetch(PDO::FETCH_ASSOC));
