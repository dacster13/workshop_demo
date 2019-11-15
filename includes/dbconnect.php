<?php 
$host = "localhost";
$dbname = "workshop";
$user = "root";
$password = "";
$charset = "utf8mb4";
$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
); 

$dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";

try {
    $pdo = new PDO($dsn, $user, $password, $options);
    //echo "successfully connected to database";
} catch (\Exception $e) {
    trigger_error($e->getMessage());
}