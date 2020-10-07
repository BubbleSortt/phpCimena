<?php
session_start();//Запускаем сессию

require_once 'db.php';

$login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
//filter_var фильтрует строку
//вторым параметром передается тип фильтрации(как я понимаю он ее отфарматирует к строковому типу
//уберет весь Html и другие симвлоы)
$password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);

$_SESSION['reg_data'] = array(login => $login, password=>$password);
$_SESSION['messages'] = array();
if(mb_strlen($login) < 5 || mb_strlen($login) > 90)
{
    $_SESSION['messages']['login_error'] = "Неккоректный логин";
}

if(mb_strlen($password) < 5 || mb_strlen($password) > 30)
{
    $_SESSION['messages']['password_error'] = 'Некорректный пароль';
}

$password = md5($password);
$check_user = mysqli_query($connection, "SElECT * FROM `users` WHERE `login` = '$login' AND `password` = '$password'");

if(mysqli_num_rows($check_user))
{
    $_SESSION['messages']['success'] = "Пользователь с таким логином уже существует";
}
if(!(empty($_SESSION['messages'])))
{
    header('Location: ../registration.php');
    exit();
}
else
{
    mysqli_query($connection, "INSERT INTO `users`(`login`, `password`) VALUES ('$login', '$password')");
    header('Location: ../registration.php');
    $_SESSION['messages']['success'] = "Успешная регистрация";
}



