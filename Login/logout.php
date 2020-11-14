<?php
session_start();
//Если в сессии есть user, то удаляем
unset($_SESSION['user']);
header('Location:../index.php');