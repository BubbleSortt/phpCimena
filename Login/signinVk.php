<?php
session_start();
require_once '../includes/config.php';

if(md5($config['vk']['app_id'].$_GET['uid'].$config['vk']['secret_key']) == $_GET['hash'])
{
    $_SESSION['user'] = [
        "name" => $_GET['first_name'],
        "last_name" => $_GET['last_name'],
        "id" => $_GET['uid'],
        "login" => $_GET['first_name']." ".$_GET['last_name']
    ];
    header("Location: /");
}
else
{
    $_SESSION['messages']['auth_error'] = "Не удалось войти через Vk";
    header('Location: login.php');
}






