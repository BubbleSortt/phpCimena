<?php
session_start();//Запускаем сессию

require_once '../includes/db.php';//Подключаем файл с подключением к бд

$login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
//filter_var фильтрует строку
//вторым параметром передается тип фильтрации(он ее отформатирует к строковому типу
//уберет весь Html и другие симвлоы)
$password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);

//Записываем данные регистрации в базу,
//чтобы если зарегаться не получиться вернуть их в инпут обратно
$_SESSION['reg_data'] = array(login => $login, password=>$password);
$_SESSION['messages'] = array();

//две небольшие проверки на введенные логин и пароль
if(mb_strlen($login) < 5 || mb_strlen($login) > 90)
{
    $_SESSION['messages']['login_error'] = "Неккоректный логин";
}

if(mb_strlen($password) < 5 || mb_strlen($password) > 30)
{
    $_SESSION['messages']['password_error'] = 'Некорректный пароль';
}

//проверяем есть ли такой пользователь в бд
$check_user = mysqli_query($connection, "SElECT * FROM `users` WHERE `login` = '$login'");
if(mysqli_num_rows($check_user))
{
    $_SESSION['messages']['success'] = "Пользователь с таким логином уже существует";
}

//Если массив с сообщениями об ошибках не пустой, то возвращаем их юзеру
if(!(empty($_SESSION['messages'])))
{
    header('Location: registration.php');
    exit();
}
else
{
    //Если все норм, то хешируем пароль и сохраняеь юзера в базу
    $password = md5($password);
    mysqli_query($connection, "INSERT INTO `users`(`login`, `password`) VALUES ('$login', '$password')");
    header('Location: ../Login/login.php');//Редиректим на страницу аутентификации
    $_SESSION['messages']['success'] = "Успешная регистрация";
}



