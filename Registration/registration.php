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
            <form action="signup.php" class="form" method="post">
                <div class="form__input-wrapper">
                    <label class="form__label" for="login">Логин</label>
                    <input value = "<?php if(isset($_SESSION['reg_data']['login'])){echo $_SESSION['reg_data']['login']; unset($_SESSION['reg_data']['login']);}?>"
                           id="login" name = "login" type="text" class="form__input" placeholder="admin">
                    <?php
                        if(isset($_SESSION['messages']['login_error']))
                        {
                         ?>
                        <p style = "color: #ff0000; font-size: 12px; margin-bottom: 0">
                            <?php
                                echo $_SESSION['messages']['login_error'];
                                unset($_SESSION['messages']['login_error']);
                            ?>
                        </p>
                        <?php
                        }
                    ?>
                </div>
                <div class="form__input-wrapper">
                    <label class="form__label" for="password">Пароль</label>
                    <input value ="<?php if(isset($_SESSION['reg_data']['password'])){echo $_SESSION['reg_data']['password']; unset($_SESSION['reg_data']['password']);}?>"
                           id="password" name = "password" type="password" class="form__input" placeholder="qwerty">
                    <?php
                    if(isset($_SESSION['messages']['password_error']))
                    {
                        ?>
                        <p style = "color: #ff0000; font-size: 12px; margin-bottom: 0">
                            <?php
                            echo $_SESSION['messages']['password_error'];
                            unset($_SESSION['messages']['password_error']);
                            ?>
                        </p>
                        <?php
                    }
                    ?>
                </div>
                <button class="form__submit" type="submit">Регистрация</button>
                <?php
                if(isset($_SESSION['messages']['success']))
                {
                ?>
                <p style = "color: green; font-size: 12px; margin: 0 auto">
                <?php
                echo $_SESSION['messages']['success'];
                unset($_SESSION['messages']['success']);
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