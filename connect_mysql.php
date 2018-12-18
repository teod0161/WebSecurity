<?php

require __DIR__ . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$servername = getenv('MYSQL_HOST');
$username = getenv('MYSQL_USERNAME');
$password = getenv('MYSQL_PASSWORD');
$database = getenv('MYSQL_DATABASE');

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
    die($e->getMessage());
}
