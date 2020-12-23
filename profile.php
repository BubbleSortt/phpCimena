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
        <p class="lk-id">Кинотеатры Волгограда: </p>
        <div>
            <script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A37c4bbaa76ef44684aa226d76f79938c67a552631c678506f875c7a38fe32c01&amp;
            width=500&amp;height=400&amp;lang=ru_RU&amp;scroll=true"></script>
        </div>
        <a href="Login/logout.php" class="header__link lk-logout" style = " margin-top: 5px; border-radius: 10%; padding:5px; background-color: red; max-width: 70px; display: flex; justify-content: center">
            <span style = "color: white">Выход</span>
        </a>

    </div>
</main>

<?php
include_once "Shared/js.php";
?>
</body>
</html>