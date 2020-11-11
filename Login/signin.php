<?php
session_start();

require_once '../includes/db.php';//ПОдключаем бд

$login = $_POST['login'];
$password = md5($_POST['password']);//Пароль сразу хешируем

$_SESSION['auth_data'] = array('login' => $login);//Сохраняем логин, так как в инпут будем возвращать только его

//Ищем юзера в базе
$check_user = mysqli_query($connection, "SElECT * FROM `users` WHERE `login` = '$login' AND `password` = '$password'");

//Если есть такой юзер, то создаем в сессии массив user, куда пишем id и login
if(mysqli_num_rows($check_user))
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
    header('Location: login.php');
}

