<?php
session_start();
if(!isset($_SESSION['user']))
{
   header("Location: Login/login.php");
}
?>

<!doctype html>
<html lang="ru">
<head>
<?php
include_once "Shared/links.php";
include_once "Shared/metaInf.php";
?>
</head>

<body>
<?php
include_once "Shared/header.php";
?>
<main class="">
    <div class="container">
        <h1 class="new-item-main-title">Личный кабинет</h1>

        <p class="lk-hello">Привет, <span class="lk-login"><?php echo $_SESSION['user']['login']?></span> </p>
        <p class="lk-id">Ваш идентификатор: <span><?php echo $_SESSION['user']['id']?></span></p>
        <a href="Login/logout.php" class="header__link lk-logout">Выход</a>
    </div>
</main>

<?php
include_once "Shared/js.php";
?>
</body>
</html>