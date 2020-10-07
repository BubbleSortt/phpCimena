<?php
session_start();
require_once 'db.php';

$login = $_POST['login'];
$password = md5($_POST['password']);

$_SESSION['auth_data'] = array(login => $login);

$check_user = mysqli_query($connection, "SElECT * FROM `users` WHERE `login` = '$login' AND `password` = '$password'");

if(mysqli_num_rows($check_user) > 0)
{
    $user = mysqli_fetch_assoc($check_user);
    $_SESSION['user'] = [
        "id" => $user['id'],
       "login" => $user['login']
    ];
    header("Location: /");
}
else
{
    $_SESSION['messages']['auth_error'] = "Неверный логин или пароль";
    header('Location: ../auth.php');
}

