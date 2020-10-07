<?php
session_start();
if(!isset($_SESSION['user']))
{
    header("Location: auth.php");
}
?>

<!doctype html>
<html lang="ru">
<head>
<?php
include_once "includes/links.php";
include_once "includes/metaInf.php";
?>
</head>
<body>
<?php
include_once "includes/header.php";
include_once "includes/metaInf.php";
?>
<main class="">
    <h1 class="new-item-main-title">Любимое</h1>
    <section class="new-item">
        <div class="container">
            <div class="new-item__list">
                <?php
                $user_id = $_SESSION['user']['id'];
                $user_favorites_id = mysqli_query($connection,
                    "SELECT `film_id` FROM `favorites` WHERE `user_id`= '$user_id'");
                while($fav_id = mysqli_fetch_assoc($user_favorites_id))
                {
                    $f_id = $fav_id['film_id'];
                    $favorite_film = mysqli_query($connection, "SELECT * FROM `films` WHERE `id` = '$f_id' ORDER BY `id`");
                    $f_film = mysqli_fetch_assoc($favorite_film);
                ?>
                    <div class="item-wrapper">
                        <div class="popular__item item">
                            <a href="#" class="item__img-wrap">
                                <img src="<?php echo $f_film['image']?>" alt="">
                            </a>
                            <div class="item__info">
                                <a href="#" class="item__title"><?php echo $f_film['title']?></a>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>

    </section>

</main>

<?php include_once "includes/js.php"?>
</body>
</html>