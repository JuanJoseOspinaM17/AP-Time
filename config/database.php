<?php
$host = getenv('DB_HOST') ?: 'localhost';
$db = getenv('DB_NAME') ?: 'ap_time';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false];
try {
    $pdo = new PDO($dsn,$user,$pass,$options);
}  catch (PDOException $e) {
    http_response_code(500);
    die('No se pudo conectar con la base de datos AP TIME. Importa primero database/ap_time.sql y verifica que MySQL esté encendido.');
}
