<?php
session_start();
if(isset($_SESSION['user'])){
    header('Location: ../profile.php');
}
?>
<!doctype html>
<html lang="ru">
<head>
    <?php require_once "../Shared/metaInf.php" ?>
    <?php require_once "../Shared/links.php" ?>
</head>
<body>
<?php
include "../Shared/header.php";
?>
<main>
    <div class="container">
        <div class="main-form">
            <form action="signin.php" class="form" method="post">
                <div class="form__input-wrapper">
                    <label class="form__label" for="login">Логин</label>
                    <!--Строчка value это проверка есть ли в сессии какие - то сообщения о проблемах с авторизацией
                    в данном случае для логина-->
                    <input id="login" name="login" type="text"
                          value="<?php if(isset($_SESSION['auth_data']['login'])){echo $_SESSION['auth_data']['login'];
                        unset($_SESSION['auth_data']['login']);}?>"  class="form__input">
                </div>
                <div class="form__input-wrapper">
                    <label class="form__label" for="password">Пароль</label>
                    <input id="password" name="password" type="password"
                            class="form__input">
                </div>
                <button class="form__submit" type="submit">Вход</button>
                <?php
                if(isset($_SESSION['messages']['auth_error']))
                {
                    ?>
                    <p style = "color: red; font-size: 12px; margin: 0 auto">
                        <?php
                        echo $_SESSION['messages']['auth_error'];
                        unset($_SESSION['messages']['auth_error']);
                        ?>
                    </p>
                    <?php
                }
                ?>
            </form>
        </div>
    </div>
</main>
<?php include_once "../Shared/js.php" ?>
</body>
</html>