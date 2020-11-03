<?php

require_once "config.php";
//подключение без pdo
$connection =mysqli_connect(
    $config['db']['server'],
    $config['db']['username'],
    $config['db']['password'],
    $config['db']['name']
);

$db_host = $config['db']['server'];
$db_username = $config['db']['username'];
$db_password = $config['db']['password'];
$db_name = $config['db']['name'];
$db_charset = "utf8";

$dsn = "mysql:host=$db_host;dbname=$db_name;charset=$db_charset";

$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$pdo = new PDO($dsn, $db_username, $db_password, $opt);

if($connection == false)
{
    echo 'Не удалось подключиться к базе данных! <br>';
    echo mysqli_connect_error();
    exit();
}


